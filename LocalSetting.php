<?php
//this file Will include all basic setting
//Load SQL Setting

define('IN_SKYOJSYSTEM',1);

require_once('config/config.php');
error_reporting(E_ALL);
session_start();
$_G = array();
$_G['uid'] = 0;

if( isset($_COOKIE['token']) && 
    isset($_SESSION['logintoken'][$_COOKIE['token']]))
{
    $accout = $_SESSION['logintoken'][$_COOKIE['token']];
    $_G['uid'] = $accout['uid'];
    $_G['email'] = $accout['email'];
    $_G['nickname'] = $accout['nickname'];
}

$_E = array();

$_E['ROOT'] = __DIR__;
$_E['site']['name']='TNFSH Online Judge(Test)';
$_E['site']['host']='http://ulkk2285d976.lfswang.koding.io/TNFSHOnlineJudge/';

$_E['EnableMathJax'] = 1;
$_E['uesLocalMathJaxFile'] = 0;

?>