<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function rank_api_rebuildHandle()
{
    global $SkyOJ,$_E,$_G;

    try{
        $sb_id = $SkyOJ->UriParam(3);

        $user = $SkyOJ->UriParam(4);

        if( !\SKYOJ\check_tocint($sb_id) )
            throw new \Exception('ID Error');
        if( !\SKYOJ\check_tocint($user) )
            throw new \Exception('User Error');

        $sb = new \SkyOJ\Scoreboard\ScoreBoard();
        if( !$sb->load($sb_id) )
            throw new \Exception('Load Scoreboard error!');
        $sb_id = $sb->sb_id;

        if( $user==0 )
        {
            if($sb->rebuildAllable($SkyOJ->User))
            {
                $sb->rebuild();
                \SKYOJ\throwjson('SUCC','yes');
            }
            else
            {
                throw new \Exception('Access denied');
            }
        }

        if( $sb->rebuildUserable($SkyOJ->User,$user) )
        {
            $sb->rebuild([$user]);
            \SKYOJ\throwjson('SUCC','yes');
        }
        throw new \Exception('Access denied');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
    
}