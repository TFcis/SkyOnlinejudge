<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function viewPlayingSubpageHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;
    $param = $SkyOJ->UriParam(4);
    $addition = $SkyOJ->UriParam(5)??'';
    static $prob_prefix = 'prob_';

    if( substr($param,0,strlen($prob_prefix))==$prob_prefix )
    {
        //TODO Some bug with substr?
        $param = preg_replace('/^'.preg_quote($prob_prefix,'/').'/','', $param);
        if( !viewPlayingProblem($contest,$param,$addition) )
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

function viewPlayingProblem(\SKYOJ\Contest $contest,string $ptag,string $filename):bool
{
    global $SkyOJ,$_E,$_G;
    $probs = $contest->get_user_problems_info($_G['uid']);
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
    $prob_data = new \SkyOJ\Problem\Container();
    if( !$prob_data->load($prob->pid) )
    {
        return false;
    }

    if( strlen($filename)>0 )
    {
        require_once($_E['ROOT'].'/function/problem/problem_view.php');
        \SKYOJ\Problem\viewachieveHandle($prob_data,$filename,true);
        exit(0);
    }

    $_E['template']['problem'] = $prob_data;
    \Render::renderSingleTemplate('view_problem','contest');
    return true;
}
