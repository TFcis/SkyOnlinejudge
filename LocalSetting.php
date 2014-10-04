<?php
//this file Will include all basic setting
//Load SQL Setting

define('IN_SKYOJSYSTEM',1);

require_once('config/config.php');
error_reporting(E_ALL);
session_start();
$_E = array();

$_E['ROOT'] = __DIR__;
$_E['site']['name']='TNFSH Online Judge(Test)';

$_E['EnableMathJax'] = 1;
$_E['uesLocalMathJaxFile'] = 0;

?>