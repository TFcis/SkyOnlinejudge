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

#Permission
$permission = array();
$permission['guest']['uid'] = "0";

$_G = $permission['guest'];

#Log System Setting
$_E['logsys']['logfile'] = $_E['ROOT'].'/data/log.txt';
$_E['logsys']['msgshower']['enabled'] = true;
$_E['logsys']['msgshower']['ip'] = 'localhost';
$_E['logsys']['msgshower']['port'] = '19620';

#Error Message
$_E['template']['alert'] = '';

#Problem
$_E['problem']['path'] = '/data/problem/';

#MathJax
$_E['EnableMathJax'] = 0;
$_E['uesLocalMathJaxFile'] = 0;

#Codepad
$_E['Codepad']['enabled'] = true;
$_E['Codepad']['allowguestsubmit'] = false;
$_E['Codepad']['maxcodelen'] = 15000;

if( file_exists('LocalSetting.php') )
{
    require_once('LocalSetting.php');
}


#Set Constant Strings
$_E['SITEROOT'] = "//".$_SERVER['SERVER_NAME'].$_E['SITEDIR'];
if( isset($cgUseHTTPS) && $cgUseHTTPS === true )//is needed?
{
    $_E['SITEROOT'] = 'https:'.$_E['SITEROOT'];
}

//test
$QUEST = '';
if( isset($_SERVER['PATH_INFO']) )
    $QUEST = $_SERVER['PATH_INFO'];
if( !empty($QUEST) ) //remove first '/'
    $QUEST = substr($QUEST,1);
$QUEST = explode('/',$QUEST);
