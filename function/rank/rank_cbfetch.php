<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
ignore_user_abort(true);
set_time_limit(0);
//
$cachetime = 24*60*60; //second
$BID = safe_get('id');
$refresh_user = safe_get('user');
$syscall = safe_get('scallid');
$sysflag = false;

if(!$BID || !$refresh_user){
    throwjson('error','Data ERROR');
}

$boarddata = getCBdatabyid($BID);
if($boarddata === false)
{
    throwjson('error','No Such ID');
}

$boarduserlist = extend_userlist($boarddata['userlist']);
if( $boarduserlist === false )
{
    throwjson('error','userlist format error');
}

//check permissions
if( $syscall ){
    if( !is_string($syscall) ||
        !isset( $_SESSION["cbsyscall"] ) || !isset( $_SESSION["cbsyscall"][$syscall] ) ){
         throwjson('error','No Such Key');
    }
    else{
        $sysflag = true;
        $refresh_user = 'all';
        $BID = $_SESSION["cbsyscall"][$syscall];
        unset($_SESSION["cbsyscall"][$syscall]);
    }
}
elseif( userControl::getpermission($boarddata['owner']) ){
    if($refresh_user !== 'all'){
        if( !in_array(intval($refresh_user),$boarduserlist) ){
            throwjson('error','No Such UID');
        }
    }
}
elseif( $_G['uid'] && $refresh_user != $_G['uid'] ){
    throwjson('error','No permissions!');
}
else{
    throwjson('error','No permissions');
}

#check and create cache if not avaibile
$cachefile = DB::loadcache("cache_board_$BID");
if( $refresh_user === 'all' || $cachefile === false ){
    if( $build_data = buildcbboard($BID,array()) )
    {
        DB::putcache("cache_board_$BID",
            array('data'=>$build_data,'time'=>time()+$cachetime)
            ,'forever');
        $cachefile = $build_data;
    }
    else
    {
        throwjson('error','Default buildcbboard() error!');
    }
}
else
{
    $cachefile = $cachefile['data'];
}

if( DB::loadcache("cbfetch_work_$BID")){
    throwjson('error','WORKING! try later');
}

if( $refresh_user === 'all' ){
    DB::putcache("cbfetch_work_$BID",true,'forever');
    $refresh_user = $boarduserlist;
}
else
    $refresh_user = array($refresh_user);


foreach( $refresh_user as $uid )
{
    if( $child = buildcbboard($BID,array($uid)) )
    {
        $cachefile = merge_cb_rate_map($cachefile,$child);
        DB::putcache("cache_board_$BID",
                    array('data'=>$cachefile,'time'=>time()+$cachetime)
                    ,'forever');
    }
    else
    {
        DB::deletecache("cbfetch_work_$BID");
        throwjson('error','ERROR ABORT!');
    }
    usleep(50000);
}

$sortrule = 'sort_by_ac';
$u = $cachefile['userlist'];
$usernum = count($u);
for($i = 0; $i < $usernum; $i++)
{
    for($j = $i+1; $j < $usernum; $j++)
    {
        switch($sortrule)
        {
            case 'sort_by_ac' :
                if( $cachefile['userdetail'][$u[$i]]['statistics']['90'] <
                    $cachefile['userdetail'][$u[$j]]['statistics']['90'])
                {
                    $t = $u[$i];
                    $u[$i] = $u[$j];
                    $u[$j] =$t;
                }
                break;
            case 'sort_by_id_desc' :
                if($u[$i] < $u[$j])
                {
                    $t = $u[$i];
                    $u[$i] = $u[$j];
                    $u[$j] =$t;
                }
                break;
        }
    }
}

$cachefile['userlist'] = $u;
DB::putcache("cache_board_$BID",
            array('data'=>$cachefile,'time'=>time()+$cachetime)
            ,'forever');
DB::deletecache("cbfetch_work_$BID");
throwjson('SUCC','build!');