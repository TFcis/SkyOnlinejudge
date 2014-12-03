<?php
require_once('GlobalSetting.php');

if(isset($_GET['old']))
Render::render('index_1','index');
else
Render::render('index','index');
