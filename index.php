<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

$SkyOJ->RegisterHandle('index','\\Index',null,true);
$SkyOJ->run();
function Index(){
    global $SkyOJ;
    $param = $SkyOJ->UriParam(1);
    switch($param){
        case 'old':
            Render::render('index_1', 'index');
            break;
        default:
            Render::render('index', 'index');
            break;
    }
}
