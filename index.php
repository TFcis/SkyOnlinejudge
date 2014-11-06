<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');

if(isset($_GET['old']))
Render::render('index_1','index');
else
Render::render('index','index');
