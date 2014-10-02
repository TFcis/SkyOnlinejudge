<?php
//this file Will include all basic setting
//Load SQL Setting

define('IN_OJSYSTEM',1);

require_once('config/config.php');

$_E = array();

$_E['ROOT'] = __DIR__;
$_E['site']['name']='TNFSH Online Judge(Test)';

$_E['EnableMathJax'] = 0;
$_E['uesLocalMathJaxFile'] = 0;

?>