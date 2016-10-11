<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle()
{
    global $SkyOJ,$_E,$_G;
    try{
        $sb_id = $SkyOJ->UriParam(2);

        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('SBID Error');

        $sb = new \SKYOJ\ScoreBoard($sb_id);
        $sb_id = $sb->sb_id();

        \SKYOJ\nickname($users = $sb->GetUsers());
        $problems = $sb->GetProblems();

        $sb->make_inline();
        $_E['template']['sb'] = $sb;
        $_E['template']['tsb'] = $sb->GetScoreBoard();
        \Render::render('rank_scoreboard', 'rank');
    }catch(\Exception $e)
    {
        Render::errormessage('記分板 Load Failed!'.$e->getMessage(),'RANK');
    }
}/*
$id = null;
$data = false;

$_E['template']['cbrebuild'] = false;
$table_statsboard = 'statsboard';
$tboard = DB::tname($table_statsboard);

//Check id
if ($id = safe_get('id')) {
    if (!is_string($id) || !preg_match('/^[0-9]+$/', $id)) {
        $id = null;
    } else {
        $id = intval($id);
        $oriboarddata = getCBdatabyid($id);
    }
}

if ($id == null || !$oriboarddata) {
    Render::errormessage('記分板 Load Failed!', 'RANK');
    include 'rank_list.php';
    exit(0);
}

$state = $oriboarddata['state'];

//版面

switch ($state) {
    case 0:
        $boarddata = null;
        Render::errormessage('Closed!', 'RANK');
        break;
    case 1: //Normal
        $boarddata = PrepareBoardData($oriboarddata);
        break;
    case 2:
        if ($oriboarddata['state'] == 2) {
            //FREEZE

            if (Render::html_cache_exists("cb_cache_$id")) {
                $_E['template']['rank_cb_fzboard'] = "cb_cache_$id";
            } else {
                $_E['template']['rank_cb_fzboard'] = false;
            }
        }
        $boarddata = true;
        break;
    default:
        $boarddata = null;
}

if ($boarddata == null) {
    Render::errormessage('記分板 Load Failed!', 'RANK');
    include 'rank_list.php';
    exit(0);
}

//頁面資訊

//rebuild
if ($_E['template']['cbrebuild']) {
    $key = uniqid('cbedit', true);
    $_SESSION['cbsyscall'][$key] = $id;
    $_E['template']['cbrebuildkey'] = $key;
}

//COMMON
$_E['template']['id'] = $id;
$_E['template']['state'] = $oriboarddata['state'];
$_E['template']['announce'] = $oriboarddata['announce'];
$_E['template']['title'] = $oriboarddata['name'];
$_E['template']['owner'] = $oriboarddata['owner'];
//導覽列

//it sholuld be use SQL!
$_E['template']['leftid'] = 0;
$_E['template']['rightid'] = 0;
$res = DB::query("SELECT `id` FROM `$tboard` WHERE `id`>$id AND `state`>0 LIMIT 1");
if ($res && $tmp = DB::fetch($res)) {
    $_E['template']['rightid'] = $tmp['id'];
}

$res = DB::query("SELECT `id` FROM `$tboard` WHERE `id`<$id AND `state`>0 ORDER BY `id` DESC LIMIT 1");
if ($res && $tmp = DB::fetch($res)) {
    $_E['template']['leftid'] = $tmp['id'];
}

$hcount = DB::countrow($table_statsboard, "`id`>$id AND `state` > 0");
$_E['template']['homeid'] = 1 + intval($hcount / 10);

Render::render('rank_statboard_cm', 'rank');
*/