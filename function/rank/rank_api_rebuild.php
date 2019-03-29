<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function rank_api_rebuildHandle()
{
    global $SkyOJ,$_E,$_G;

    try{
        set_time_limit(0);
        ignore_user_abort(true);
        
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
                $res = $sb->rebuild();
                if($res[0])
                    \SKYOJ\throwjson('SUCC','yes');
                else
                    throw new \Exception($res[1]);
            }
            else
            {
                throw new \Exception('Access denied');
            }
        }
        else if( $sb->rebuildUserable($SkyOJ->User,$user) )
        {
            $res = $sb->rebuild();
            if($res[0])
                \SKYOJ\throwjson('SUCC','yes');
            else
                throw new \Exception($res[1]);
        }
        throw new \Exception('Access denied');
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
    
}