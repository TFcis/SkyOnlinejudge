<?php namespace SKYOJ\Rank;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function rank_api_newHandle()
{
    global $_G,$_E;
    try{
        if( !\userControl::isAdmin($_G['uid']) )
        {
            \SKYOJ\throwjson('error', 'Access denied');
        }

        $title = \SKYOJ\safe_post('title');
        $type  = \SKYOJ\safe_post_int('type');
        
        if( !\SKYOJ\ScoreBoardTypeEnum::isValidValue($type) )
        {
            \SKYOJ\throwjson('error', 'Type Error');
        }
        
        $sb_id = \SKYOJ\ScoreBoard::CreateNew($title,$type);
        \SKYOJ\throwjson('SUCC',$sb_id);
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}