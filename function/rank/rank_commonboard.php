<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
$id = null;
$data = false;

//$_E['template']['dbg'] = '';
$_E['template']['cbrebuild'] = false; 

//Check id
if( $id = safe_get('id') )
{
    if(!is_string($id) || !preg_match('/^[0-9]+$/',$id))
    {
        $id = null;
    }
    else
    {
        $id = intval($id);
        if( $cache = DB::loadcache("cache_board_$id") )
        {
            $boarddata = $cache['data'];
            $_E['template']['buildtime'] = $cache['time'];
            if( $cache['time']<time() ){
                $_E['template']['cbrebuild'] = true;
            }
        }
        else
        {
            $boarddata = buildcbboard($id,array());
            if($boarddata)
            {
                $time = time();
                $_E['template']['buildtime'] = $time;
                DB::putcache("cache_board_$id",
                            array('data'=>$boarddata,'time'=>$time+900),
                            'forever');
                $_E['template']['cbrebuild'] = true;
            }
            else
            {
                $id = null;
            }
        }
    }
}

if( $id == null || !$boarddata )
{
    $_E['template']['alert'].="沒有這一個記分板";
    include('rank_list.php');
    exit(0);
}

//頁面資訊

if($_E['template']['cbrebuild'])
{
    $key = uniqid('cbedit',true);
    $_SESSION["cbsyscall"][$key] = $id;
    $_E['template']['cbrebuildkey'] = $key;
}
$_E['template']['buildtime'] = date("Y-m-d H:i:s",$_E['template']['buildtime']);
$_E['template']['title'] = $boarddata['name'];
$_E['template']['plist'] = $boarddata['probinfo'];
$_E['template']['user']  = $boarddata['userlist'];
$_E['template']['owner'] = $boarddata['owner'];
$_E['template']['id'] = $id;
$_E['template']['userdetail'] = $boarddata['userdetail'];
//導覽列

$maxid = DB::countrow('statsboard');
//it sholuld be use SQL!
//$res = DB::query("SELECT `id` AS 'id' FROM `tojtest_statsboard` WHERE `id`<>3 ORDER BY `id`, abs(`id`-3) LIMIT 0,2  ;");
$_E['template']['leftid'] = 0;
$_E['template']['rightid'] = 0;
if($id-1 > 0) $_E['template']['leftid'] = $id-1;
if($id+1 <= $maxid)$_E['template']['rightid'] = $id+1;
$hcount = DB::countrow('statsboard',"`id`>$id");
$_E['template']['homeid'] = 1 + intval($hcount/10);


foreach($boarddata['userlist'] as $uid)
{
    foreach($boarddata['problems'] as $pname)
    {
        $_E['template']['s'][$uid][$pname] = verdictIDtoword($boarddata['ratemap'][$uid][$pname]);
    }
}
#add nickname
nickname($boarddata['userlist']);

Render::render('rank_statboard_cm','rank');