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
        $_E['template']['contest'] = $contest;

        $reg_state = $contest->user_regstate($_G['uid']);
        if( $reg_state === \SKYOJ\ContestTeamStateEnum::NoRegister )
        {
            //TODO reheader to register page
            if( \SKYOJ\ContestUserRegisterStateEnum::allow($contest->register_type) )
            {
                \Render::render('contest_reg', 'contest');
                exit(0);
            }
            throw new \Exception('register not open!');
        }

        if( !\SKYOJ\ContestTeamStateEnum::allow($reg_state) )
        {
            switch($reg_state)
            {
                case \SKYOJ\ContestTeamStateEnum::Pending:
                    \Render::render('contest_reg_pending', 'contest');
                    break;
                case \SKYOJ\ContestTeamStateEnum::Reject:
                    \Render::render('contest_reg_reject', 'contest');
                    break;
                case \SKYOJ\ContestTeamStateEnum::Dropped:
                    \Render::render('contest_reg_dropped', 'contest');
                    break;
                default:
                    throw new \Exception('Unknown Team State Code : '.$reg_state);
            }
            exit(0);
        }

        if( $contest->ispreparing() )
        {
            \Render::render('contest_preparing', 'contest');
            exit(0);
        }

        if( $contest->isplaying() )
        {
            viewPlayingHandle($contest);
            exit(0);
        }

        \Render::render('contest_gg', 'contest');
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}

function viewPlayingHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;
    if( !$contest->isplaying() )
    {
        throw new \Exception('viewPlayingHandle() called but not accetp playing contest!');
    }
    if( $SkyOJ->UriParam(3)==='subpage' )
    {
        require_once('contest_view_sub.php');
        viewPlayingSubpageHandle($contest);
    }
    #Get Common info
    $prob_info = $contest->get_all_problems_info();

    \Render::render('contest_playing_main', 'contest');
}