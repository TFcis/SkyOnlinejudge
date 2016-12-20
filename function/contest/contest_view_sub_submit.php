<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function sub_submitHandle(\SKYOJ\Contest $contest)
{
    global $_G,$_E,$SkyOJ;
    $pid = $SkyOJ->UriParam(5)??null;
    if( $pid === null ){;
        \Render::renderSingleTemplate('view_submit_select','contest');
        exit(0);
    }

    $problem = new \SKYOJ\Problem($pid);
    $pid = $problem->pid();

    if( $pid===null )
        throw new \Exception('Load Problem Error!');
    $judge = null;
    $judgename = $problem->GetJudge();
    if( \Plugin::loadClassFileInstalled('judge',$judgename)!==false )
        $judge = new $judgename;

    $_E['template']['problem'] = $problem;
    $_E['template']['compiler'] = $judge->get_compiler();
    \Render::renderSingleTemplate('view_submit','contest');
    exit(0);
}