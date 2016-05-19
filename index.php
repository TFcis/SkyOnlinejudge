<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

LOG::msg(Level::Debug, 'UserInfo', $_G);
if (isset($_GET['old'])) {
    require_once $_E['ROOT'].'/function/common/forminfo.php';
    $formInfo = [
        'data' => [
            ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
            ['type' => 'hr'],
            ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
            ['type' => 'text', 'name' => 'A', 'title' => 'URL'],
            ['type' => 'submit', 'id' => 'install', 'option' => ['info' => '']],
        ],
    ];
    $p = new FormInfo($formInfo);
    LOG::msg(Level::Debug, 'forminfo',$p);
    Render::render('index_1', 'index');
} elseif (isset($_GET['test'])) {
    Render::render('common_codepanel');
} else {
    Render::render('index', 'index');
}
