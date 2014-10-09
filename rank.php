<?php
require_once('LocalSetting.php');
require_once('function/renderCore.php');
require_once('function/pluginsCore.php');

$plugins->loadClassByPluginsFolder('rank/board_other_oj');

$Render->render('rank_index','rank');