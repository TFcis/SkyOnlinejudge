<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
$id = null;
$data = false;

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
        $oriboarddata = getCBdatabyid($id);
    }
}

if( $id == null || !$oriboarddata )
{
    Render::errormessage("記分板 Load Failed!",'RANK');
    include('rank_list.php');
    exit(0);
}

$state = $oriboarddata['state'];

//版面

switch($state)
{
    case 0:
        $boarddata = null;
        Render::errormessage("Closed!",'RANK');
        break;
    case 1: //Normal
        $boarddata = PrepareBoardData($oriboarddata);
        break;
    case 2:
        if( $oriboarddata['state'] == 2 ) //FREEZE
        {
            if( Render::html_cache_exists("cb_cache_$id") )
                $_E['template']['rank_cb_fzboard'] = "cb_cache_$id"; 
            else
                $_E['template']['rank_cb_fzboard'] = false;
        }
        $boarddata=true;
        break;
    default:
        $boarddata = null;
}

if( $boarddata == null )
{
    Render::errormessage("記分板 Load Failed!",'RANK');
    include('rank_list.php');
    exit(0);
}

#頁面資訊

#rebuild
if( $_E['template']['cbrebuild'] )
{
    $key = uniqid('cbedit',true);
    $_SESSION["cbsyscall"][$key] = $id;
    $_E['template']['cbrebuildkey'] = $key;
}


#COMMON
$_E['template']['id'] = $id;
$_E['template']['state'] = $oriboarddata['state'];
$_E['template']['announce'] = $oriboarddata['announce'];
$_E['template']['title']    = $oriboarddata['name'];
$_E['template']['owner']    = $oriboarddata['owner'];
//導覽列

$maxid = DB::countrow('statsboard','`state` > 0');
//it sholuld be use SQL!
//$res = DB::query("SELECT `id` AS 'id' FROM `tojtest_statsboard` WHERE `id`<>3 ORDER BY `id`, abs(`id`-3) LIMIT 0,2  ;");
$_E['template']['leftid'] = 0;
$_E['template']['rightid'] = 0;
if($id-1 > 0) $_E['template']['leftid'] = $id-1;
if($id+1 <= $maxid)$_E['template']['rightid'] = $id+1;
$hcount = DB::countrow('statsboard',"`id`>$id AND `state` > 0");
$_E['template']['homeid'] = 1 + intval($hcount/10);



#add nickname


Render::render('rank_statboard_cm','rank');