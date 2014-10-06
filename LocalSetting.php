<?php
//this file Will include all basic setting
//Load SQL Setting

define('IN_SKYOJSYSTEM',1);
session_start();

require_once('config/config.php');
require_once('function/userControl.php');
error_reporting(E_ALL);

userControl::intro();

$_E = array();

$_E['ROOT'] = __DIR__;
$_E['site']['name']='Sky Online Judge(Test)';
$_E['site']['host']='http://ulkk2285d976.lfswang.koding.io/TNFSHOnlineJudge/';

$_E['EnableMathJax'] = 1;
$_E['uesLocalMathJaxFile'] = 0;

?>