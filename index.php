<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

LOG::msg(Level::Debug, 'UserInfo', $_G);
if (isset($_GET['old'])) {
    require_once $_E['ROOT'].'/function/common/forminfo.php';
    $formInfo = [
        'data' => [
            new HTML_INPUT_HIDDEN(['name' => 'data','value'=>'1']),
            new HTML_INPUT_TEXT(['name' => 'A', 'option' => ['help_text' => 'URL']]),
            new HTML_INPUT_PASSWORD(['name' => 'password', 'option' => ['help_text' => '密碼']]),
            new HTML_HR(),
            new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出']),
        ],
    ];
    $_E['template']['form'] = new FormInfo($formInfo);
    LOG::msg(Level::Debug, 'forminfo',$_E['template']['form']);
    Render::render('index_1', 'index');
} elseif (isset($_GET['test'])) {
    Render::render('common_codepanel');
} else {
    Render::render('index', 'index');
}
