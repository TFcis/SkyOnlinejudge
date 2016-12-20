<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewPlayingSubpageHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;
    $param = $SkyOJ->UriParam(4);
    static $prob_prefix = 'prob_';

    if( substr($param,0,strlen($prob_prefix))==$prob_prefix )
    {
        //TODO Some bug with substr?
        $param = preg_replace('/^'.preg_quote($prob_prefix,'/').'/','', $param);
        if( !viewPlayingProblem($contest,$param) )
        {
            \Render::renderSingleTemplate('nonedefined');
        }
        exit(0);
    }
    switch( $param )
    {
        case 'log':
        case 'submit':
        case 'scoreboard':
            break;
        default:
            \Render::renderSingleTemplate('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/contest/contest_view_sub_$param.php";
    $func     = __NAMESPACE__ ."\\sub_{$param}Handle";

    require_once($funcpath);
    $func($contest);
}

function viewPlayingProblem(\SKYOJ\Contest $contest,string $ptag):bool
{
    global $SkyOJ,$_E,$_G;
    $probs = $contest->get_all_problems_info();
    $prob = null;
    foreach($probs as $row)
    {
        if( $row->ptag == $ptag )
        {
            $prob = $row;
            break;
        }
    }
    if( !isset($prob) || !\SKYOJ\ContestProblemStateEnum::allow($prob->state)  )
    {
        return false;
    }

    #TODO Use common object
    $prob_data = new \SKYOJ\Problem($prob->pid);
    if( $prob_data->pid()===null )
    {
        return false;
    }

    $_E['template']['problem'] = $prob_data;
    \Render::renderSingleTemplate('view_problem','contest');
    return true;
}