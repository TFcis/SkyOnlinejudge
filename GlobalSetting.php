<?php

//Default Setting
//DON'T CHANGE THIS FILE!
//If you want to replace setting , yout should edit LocalSetting.php

define('IN_SKYOJSYSTEM', 1);

date_default_timezone_set('Asia/Taipei');

require_once 'config/config.php';
require_once 'vendor/autoload.php';

//Environment
$_E = [];
//LanguageData
$_LG = [];

$_E['ROOT'] = __DIR__;
$_E['SITEPORT'] = '80';
$_E['SITEDIR'] = '/';
$_E['DATADIR'] = $_E['ROOT'].'/data/';

$_E['language'] = 'zh-tw';
//Site Setting
$_E['site']['admin'] = [1];
$_E['site']['name'] = 'Sky Online Judge';

//Permission
$permission = [];
$permission['guest']['uid'] = '0';

$_G = $permission['guest'];

//Log System Setting
$_E['logsys']['logfile'] = $_E['ROOT'].'/data/log.txt';
$_E['logsys']['msgshower']['enabled'] = false;
$_E['logsys']['msgshower']['ip'] = 'localhost';
$_E['logsys']['msgshower']['port'] = '19620';

//Error Message
$_E['template']['alert'] = '';

//MathJax
$_E['EnableMathJax'] = 0;
$_E['uesLocalMathJaxFile'] = 0;

//Codepad
$_E['Codepad']['enabled'] = true;
$_E['Codepad']['allowguestsubmit'] = false;
$_E['Codepad']['maxcodelen'] = 15000;

if (file_exists('LocalSetting.php')) {
    require_once 'LocalSetting.php';
}

//Set Constant Strings
$_E['SITEROOT'] = '//'.$_SERVER['SERVER_NAME'].':'.$_E['SITEPORT'].$_E['SITEDIR'];
if (isset($cgUseHTTPS) && $cgUseHTTPS === true) {
    //is needed?

    $_E['SITEROOT'] = 'https:'.$_E['SITEROOT'];
}

//test
$QUEST = '';
if (isset($_SERVER['PATH_INFO'])) {
    $QUEST = $_SERVER['PATH_INFO'];
}
if (!empty($QUEST)) { //remove first '/'
    $QUEST = substr($QUEST, 1);
}
$QUEST = explode('/', $QUEST);
