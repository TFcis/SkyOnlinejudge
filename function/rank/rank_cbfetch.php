<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
ignore_user_abort(true);
set_time_limit(0);
//
$cachetime = 2*60*60; //second
$BID = save_get('id');
$refresh_user = save_get('user');
$syscall = save_get('scallid');
$sysflag = false;

if(!is_string($BID) || !is_string($refresh_user)){
    throwjson('error','Data ERROR');
}

$boarddata = getCBdatabyid($BID);
if($boarddata === false)
{
    throwjson('error','No Such ID');
}

$boarduserlist = extend_userlist($boarddata['userlist']);

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
elseif( $boarddata['owner'] == $_G['uid'] || userControl::getpermission($boarddata['owner']) ){
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

$cachefile = DB::loadcache("cache_board_$BID");
if( $refresh_user === 'all' && $cachefile === false ){
    if( $build_data = buildcbboard($BID,''))
    {
        DB::putcache("cache_board_$BID",
            array('data'=>$build_data,'time'=>time()+$cachetime)
            ,'forever');
    }
    else
    {
        throwjson('error','build error!');
    }
}

if( DB::loadcache("cbfetch_work_$BID")){
    throwjson('error','WORKING!');
}

if( $refresh_user === 'all'){
    DB::putcache("cbfetch_work_$BID",true,'forever');
    $refresh_user = $boarduserlist;
}
else
    $refresh_user = array($refresh_user);

$cachefile = $cachefile['data'];
foreach( $refresh_user as $uid)
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
DB::deletecache("cbfetch_work_$BID");
throwjson('SUCC','build!');