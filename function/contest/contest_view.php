<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewHandle()
{
    global $SkyOJ,$_E,$_G;
    try{
        $cont_id = $SkyOJ->UriParam(2);

        if( !\SKYOJ\check_tocint($cont_id) )
            throw new \Exception('CONT_ID Error');

        $contest = new \SKYOJ\Contest($cont_id);
        if( $contest->isIdfail() )
            throw new \Exception('CONT_ID Error');

        $reg_state = $contest->user_regstate($_G['uid']);
        if( $reg_state === \SKYOJ\ContestTeamStateEnum::NoRegister )
        {
            //TODO reheader to register page
            throw new \Exception('Not register yet!');
        }

        if( !\SKYOJ\ContestTeamStateEnum::allow($reg_state) )
        {
            switch($reg_state)
            {
                case \SKYOJ\ContestTeamStateEnum::Pending:
                    \Render::render('contest_reg_pending', 'contest');
                case \SKYOJ\ContestTeamStateEnum::Reject:
                    \Render::render('contest_reg_reject', 'contest');
                case \SKYOJ\ContestTeamStateEnum::Dropped:
                    \Render::render('contest_reg_dropped', 'contest');
                default:
                    throw new \Exception('Unknown Team State Code : '.$reg_state);
            }
            \SKYOJ\NeverReach();
        }


        //$sb->make_inline();
        //$_E['template']['sb'] = $sb;
        //$_E['template']['tsb'] = $sb->GetScoreBoard();
        \Render::render('rank_scoreboard', 'rank');
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}
