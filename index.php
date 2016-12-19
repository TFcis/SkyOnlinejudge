<?php namespace SKYOJ;

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

$SkyOJ->RegisterHandle('index','\\SKYOJ\\Index',null,true);
$SkyOJ->RegisterHandle('code' ,'\\SKYOJ\\Code\\CodeHandle',$_E['ROOT'].'/code.php');
$SkyOJ->RegisterHandle('rank' ,'\\SKYOJ\\Rank\\RankHandle',$_E['ROOT'].'/function/rank/rank.php');
$SkyOJ->RegisterHandle('admin','\\SKYOJ\\Admin\\AdminHandle',$_E['ROOT'].'/admin.php');
$SkyOJ->RegisterHandle('user','\\SKYOJ\\User\\UserHandle',$_E['ROOT'].'/user.php');
$SkyOJ->RegisterHandle('problem','\\SKYOJ\\Problem\\ProblemHandle',$_E['ROOT'].'/problem.php');
$SkyOJ->RegisterHandle('chal','\\SKYOJ\\Challenge\\ChallengeHandle',$_E['ROOT'].'/challenge.php');
$SkyOJ->RegisterHandle('contest','\\SKYOJ\\Contest\\ContestHandle',$_E['ROOT'].'/function/contest/contest.php');
$SkyOJ->run();
function Index(){
    global $SkyOJ;
    $param = $SkyOJ->UriParam(1);
    switch($param){
        case 'old':
            \Render::render('index_1', 'index');
            break;
        default:
            \Render::render('index', 'index');
            break;
    }
}
