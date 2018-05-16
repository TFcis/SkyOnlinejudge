<?php namespace SkyOJ\Core;
// TODO : Get rid those old code
require_once 'GlobalSetting.php';

require_once 'function/Log.php';
require_once 'function/DB.php';

require_once 'function/userControl.php';
require_once 'function/renderCore.php';
require_once 'function/pluginsCore.php';
//Load Library
require_once 'function/common/encrypt.php';
require_once 'function/common/emnu.php';
require_once 'function/common/forminfo.php';
require_once 'function/common/common_object.php';
require_once 'function/Skyoj.lib.php';


$SkyOJ = new SkyOJ();
$SkyOJ->run();

