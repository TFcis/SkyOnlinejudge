<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function rank_api_joinHandle()
{
    global $SkyOJ,$_E,$_G;

    try{
        $sb_id = $SkyOJ->UriParam(3);
        $uid = $SkyOJ->UriParam(4);
		
        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('ID Error');
        if( !\SKYOJ\check_tocint($uid) )
            throw new \Exception('User Error');

		$uid = (int)$uid;
        $sb = new \SkyOJ\Scoreboard\ScoreBoard();
        if( !$sb->load($sb_id) )
            throw new \Exception('Load Scoreboard error!');
        $sb_id = $sb->sb_id;
		
		if( !$sb->isAllowJoin( $uid ) )
			throw new \Exception('request rejected');
		
		$users = $sb->GetUsers();
		$users[] = $uid;
		if( !$sb->SetUsers($users) )
			throw new \Exception('modify users error');

        if( !$sb->save() )
            throw new \Exception('SQL Error!');
		
        $sb1 = new \SkyOJ\Scoreboard\ScoreBoard();
        $sb1->load($sb_id);
        $sb1->make_inline();
        \SKYOJ\throwjson('SUCC','yes');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
    
}
