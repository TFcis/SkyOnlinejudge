<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container-fluid">
    <div class="row">
        <div id="image-bar"></div>
        <div class="col"><h1>編輯題目</h1></div>
    </div>
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="common-tab" data-toggle="tab" href="#common" role="tab" aria-controls="common" aria-selected="true">一般設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="testdata-tab" data-toggle="tab" href="#testdata" role="tab" aria-controls="testdata" aria-selected="false">測試資料</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                </li>
            </ul>
        
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="common" role="tabpanel" aria-labelledby="common-tab">
                    <?php \Render::renderSingleTemplate('problem_modify_common','problem') ?>
                </div>
                <div class="tab-pane fade" id="testdata" role="tabpanel" aria-labelledby="testdata-tab">
                    <?php \Render::renderSingleTemplate('problem_attach','problem') ?>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">89</div>
            </div>
        </div>
    </div>
</div>