<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

LOG::msg(Level::Debug, 'UserInfo', $_G);
if (isset($_GET['old'])) {
    require_once $_E['ROOT'].'/function/common/forminfo.php';
    $formInfo = [
        'data' => [
            new HTML_INPUT_TEXT(['block' => 'inputs', 'name' => 'A', 'option' => ['title' => 'URL']]),
            new HTML_HR(),
            /*['block' => 'inputs', 'name' => 'B', 'option' => ['title' => 'DDL']],
            ['block' => 'inputs', 'name' => 'V', 'option' => ['title' => 'FFL']],
            ['block' => 'submit', 'id' => 'install', 'option' => ['info' => '']],*/
        ],
    ];
    $p = new FormInfo($formInfo);
    LOG::msg(Level::Debug, 'forminfo',$p);
    
    Render::render('index_1', 'index');
    Render::renderForm($p,"");
} elseif (isset($_GET['test'])) {
    Render::render('common_codepanel');
} else {
    Render::render('index', 'index');
}
