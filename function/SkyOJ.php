<?php
/*
 * SKY Online Judge Site Core
 * 2016 Sky Online Judge Project
 */
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
//Load All Core
require_once 'Log.php';
require_once 'DB.php';

require_once 'userControl.php';
require_once 'renderCore.php';
require_once 'pluginsCore.php';
//Load Library
require_once 'function/common/encrypt.php';
require_once 'Skyoj.lib.php';

class _SkyOJ
{
    public function __construct()
    {
        global $_E;
        LOG::intro();
        DB::intro();
        userControl::intro();
        DB::query('SET NAMES UTF8');
    }
}
$SkyOJ = new _SkyOJ();
