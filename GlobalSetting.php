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
$_E['SITEDIR'] = '/';
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

$_E['SITEROOT'] = "//".$_SERVER['SERVER_NAME'].$_E['SITEDIR'];
if( isset($cgUseHTTPS) && $cgUseHTTPS === true)
{
    $_E['SITEROOT'] = 'https:'.$_E['SITEROOT'];
}
require_once('function/Skyoj.lib.php');
require_once('function/mysqlCore.php');
require_once('function/userControl.php');
require_once('function/renderCore.php');
require_once('function/pluginsCore.php');
userControl::intro();

//test
$QUEST = '';
if( isset($_SERVER['PATH_INFO']) )
    $QUEST = $_SERVER['PATH_INFO'];
if( !empty($QUEST) ) //remoce first '/'
    $QUEST = substr($QUEST,1);
$QUEST = explode('/',$QUEST);