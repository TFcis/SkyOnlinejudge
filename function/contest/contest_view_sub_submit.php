<?php namespace SKYOJ\Contest;

use \SkyOJ\Challenge\LanguageCode;
use \SkyOJ\Judge\Judge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function sub_submitHandle(\SKYOJ\Contest $contest)
{
    global $_G,$_E,$SkyOJ;
    $pid = $SkyOJ->UriParam(5)??null;
    if( $pid === null )
    {
        \Render::renderSingleTemplate('view_submit_select','contest');
        exit(0);
    }

    //TODO: Check submit access

    $problem = new \SkyOJ\Problem\Container();

    if( !$problem->load($pid) )
        throw new \Exception('Load Problem Error!');

    if( !$problem->isAllowSubmit($SkyOJ->User) )
    {
        if( !$SkyOJ->User->isLogin() )
            throw new \Exception('請登入後再操作');
        throw new \Exception('沒有權限');
    }
    $judge = null;
    $judge = Judge::getJudgeReference($problem->judge_profile);
    $info = $judge->getCompilerInfo();

    $_E['template']['problem'] = $problem;
    $_E['template']['compiler'] = $info;
    $_E['template']['jscallback'] = 'loadTemplate("log")';
    \Render::renderSingleTemplate('problem_submit','problem');
    exit(0);
}