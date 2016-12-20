<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}


function RegisterHandle()
{
    global $_G,$_E,$SkyOJ;
    $param = $SkyOJ->UriParam(2)??null;
    try{
        if( $param===null )
        {
            throw new \Exception('????');
        }
        if( !$_G['uid'] )
        {
            throw new \Exception('Login First!');
        }
        $contest = new \SKYOJ\Contest($param);
        if( $contest->isIdfail() )
            throw new \Exception('CONT_ID Error');
        
        
        if( !\SKYOJ\ContestUserRegisterStateEnum::allow($contest->register_type) )
        {
            throw new \Exception('Register not open!');
        }

        $state_code = \SKYOJ\ContestTeamStateEnum::Pending;
        if( $contest->register_type ==  \SKYOJ\ContestUserRegisterStateEnum::Open )
        {
            $state_code = \SKYOJ\ContestTeamStateEnum::Accept;
        }

        $table = \DB::tname('contest_user');

        if( !\DB::queryEx("INSERT INTO $table(`cont_id`, `uid`, `team_id`, `state`) VALUES (?,?,?,?)",
            $contest->cont_id(),$_G['uid'],$_G['uid'],$state_code) )
                throw new \Exception('Sql error!');
        
        header('Location:'.$SkyOJ->uri('contest','view',$contest->cont_id()));
        exit(0);
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}