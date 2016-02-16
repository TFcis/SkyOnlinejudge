<?php
require_once('GlobalSetting.php');
require_once('function/SkyOJ.php');

LOG::msg(Level::Debug,"UserInfo",$_G);
if(isset($_GET['old']))
Render::render('index_1','index');
elseif(isset($_GET['test']))
Render::render('common_codepanel');
else
Render::render('index','index');
