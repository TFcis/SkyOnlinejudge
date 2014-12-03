<?php
#Default Setting
#DON'T CHANGE THIS FILE!
#If you want to replace setting , yout should edit LocalSetting.php


define('IN_SKYOJSYSTEM',1);

session_start();
date_default_timezone_set( "Asia/Taipei" );

require_once('config/config.php');

#Environment
$_E = array();


$_E['ROOT'] = __DIR__;
#Site Setting
$_E['site']['admin']=array(1);
$_E['site']['name'] ='Sky Online Judge';

#Error Message
$_E['template']['alert'] ='';

#MathJax
$_E['EnableMathJax'] = 0;
$_E['uesLocalMathJaxFile'] = 0;


if( file_exists('LocalSetting.php') )
{
    require_once('LocalSetting.php');
}

require_once('function/Skyoj.lib.php');
require_once('function/mysqlCore.php');
require_once('function/userControl.php');
require_once('function/renderCore.php');
require_once('function/pluginsCore.php');
userControl::intro();