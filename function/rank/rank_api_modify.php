<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function rank_api_modifyHandle()
{
    global $SkyOJ,$_E,$_G;

    try{
        $sb_id = $SkyOJ->UriParam(3);

        $title = \SKYOJ\safe_post('title');
        $start = \SKYOJ\safe_post('start');
        $end   = \SKYOJ\safe_post('end');
		$allow_join = \SKYOJ\safe_post('allow_join');
        $users = \SKYOJ\safe_post('users');
        $problems = \SKYOJ\safe_post('problems');
        
        $announce = \SKYOJ\safe_post('announce');

        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('ID Error');

        $sb = new \SkyOJ\Scoreboard\ScoreBoard();
        if( !$sb->load($sb_id) )
            throw new \Exception('Load Scoreboard error!');
        $sb_id = $sb->sb_id;

        if( $sb->sb_id===null || !\userControl::isAdmin($_G['uid']) )
            throw new \Exception('Access denied');

        if( !$sb->title = $title )
            throw new \Exception('modify title error');
        if( !$sb->start = $start )
            throw new \Exception('modify start error');
        if( !$sb->end = $end )
            throw new \Exception('modify end error:');
		if( !$sb->allow_join = $allow_join )
			throw new \Exception('modify allow_join error');
        if( !$sb->SetUsers($users) )
            throw new \Exception('modify users error');
        if( !$sb->SetProblems($problems) )
            throw new \Exception('modify problems error');
        if( strtotime($start) > strtotime($end) )
            throw new \Exception('time range error');
        if( !$sb->announce = $announce )
            throw new \Exception('modify announce error');

        if( !$sb->save() )
            throw new \Exception('SQL Error!');

        $sb1 = new \SkyOJ\Scoreboard\ScoreBoard();
        $sb1->load($sb_id);
        $sb1->make_inline();
        \SKYOJ\throwjson('SUCC','yee');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
    
}

/*
//API CALL FUNCTION!
// mode = cbedit
// page : ARGS
//

//Only By Post Login Token
if (!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('CBEDIT')) {
    throwjson('error', 'Access denied');
}
$allowpage = ['cbedit', 'cbremove', 'cbfreeze', 'cbclose', 'cbopen'];

$editpage = safe_post('page');
if (!in_array($editpage, $allowpage)) {
    throwjson('error', 'No such page'.$_POST['page']);
}

$id = safe_post('id');
if (!preg_match('/^[0-9]+$/', $id)) {
    throwjson('error', 'ID error');
}
$id = (string) ((int) $id);

$board = new UniversalScoreboard($id);
if (!$board->isload()) {
    throwjson('error', 'board load error');
}
if ($id != 0 && !userControl::getpermission($board->id())) {
    throwjson('error', 'Not owner');
}
switch ($editpage) {
    case 'cbedit':
        $tb = DB::tname('statsboard');
        $removecache = false;
        $argv = ['name', 'userlist', 'problems', 'announce'];

        $cb = $board->load_data();

        //Check Premission
        if (!userControl::getpermission($cb['owner'])) {
            throwjson('error', 'Not owner');
        }
        if ($id == 0) {
            //&& Allow?

            $cb['owner'] = $_G['uid'];
        }

        //Check and Merge Argvs
        if (!($data = checkpostdata($argv))) {
            throwjson('error', 'data cbedit');
        }
        if ($data['userlist'] !== $cb['userlist'] ||
            $data['problems'] !== $cb['problems']) {
            $removecache = true;
        }
        if (!userControl::isAdmin($_G['uid'])) {
            $data['announce'] = strip_tags($data['announce']);
        }
        $cb = array_merge($cb, $data);

        //save
        $errmsg = '';
        if ($board->save_data($cb, $errmsg)) {
            if ($removecache) {
                DB::deletecache('cache_board_'.$cb['id']);
            }
            userControl::deletetoken('CBEDIT');
            throwjson('SUCC', $board->id());
        } else {
            throwjson('error', $errmsg);
        }
        break;

    case 'cbremove':
        throwjson('error', 'sql_cbremove');
        break;

    case 'cbfreeze':
        $tb = DB::tname('statsboard');
        if ($id == 0) {
            throwjson('error', 'board load error 0');
        }

        $board = new UniversalScoreboard($id);
        if (!$board->isload()) {
            throwjson('error', 'board load error');
        }

        if (!PrepareBoardData($board->load_data(), true)) {
            throwjson('error', 'cbfreeze_PrepareBoardData_fail');
        }

        if (!($html = Render::static_html('rank_statboard_cmtable', 'rank'))) {
            throwjson('error', 'cbfreeze_static_html_fail');
        }

        if (!Render::save_html_cache("cb_cache_$id", $html)) {
            throwjson('error', 'cbfreeze_static_html_fail');
        }

        DB::query("UPDATE `$tb` SET `state` = 2 WHERE `id` =".$board->id());
        throwjson('SUCC', 'modify');

        break;
    case 'cbclose':
        $tb = DB::tname('statsboard');
        if ($id == 0) {
            throwjson('error', 'board load error 0');
        }
        DB::query("UPDATE `$tb` SET `state` = 0 WHERE `id` =".$board->id());
        throwjson('SUCC', 'modify');
        break;
    case 'cbopen':
        $tb = DB::tname('statsboard');
        if ($id == 0) {
            throwjson('error', 'board load error 0');
        }
        DB::query("UPDATE `$tb` SET `state` = 1 WHERE `id` =".$board->id());
        throwjson('SUCC', 'modify');
    default:
        throwjson('error', 'modify');
        break;
}

throwjson('error', 'error');
*/