<?php
require_once('GlobalSetting.php');
//require_once('function/user/user.lib.php');

//$test = new UserInfo(1);
//$test->load_data('ojacct');

if(isset($_GET['old']))
Render::render('index_1','index');
else
Render::render('index','index');
