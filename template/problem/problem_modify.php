<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('click',function(e){
        window.location.hash = $(this).attr('href');
    });

    hash = window.location.hash;
    if( hash == '' ) hash = "#common";
    $(hash+"-tab").tab('show');
});
</script>

<div class="container-fluid">
    <div class="row">
        <div id="image-bar"></div>
        <div class="col"><h1>編輯題目</h1><small>status: <?php /*\SKYOJ\html($tmpl['problem']->admmsg)*/ ?></small></div>
    </div>
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="mainTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="common-tab" data-toggle="tab" href="#common" role="tab" aria-controls="common" aria-selected="true">一般設定</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attach-tab" data-toggle="tab" href="#attach" role="tab" aria-controls="attach" aria-selected="false">附加資料</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="testdata-tab" data-toggle="tab" href="#testdata" role="tab" aria-controls="testdata" aria-selected="false">測試資料</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                </li>
            </ul>
        
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="common" role="tabpanel" aria-labelledby="common-tab">
                    <?php \Render::renderSingleTemplate('problem_modify_common','problem') ?>
                </div>
                <div class="tab-pane fade" id="attach" role="tabpanel" aria-labelledby="attach-tab">
                    <?php \Render::renderSingleTemplate('problem_attach','problem') ?>
                </div>
                <div class="tab-pane fade" id="testdata" role="tabpanel" aria-labelledby="testdata-tab">
                <?php \Render::renderSingleTemplate('problem_testdata','problem') ?>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">89</div>
            </div>
        </div>
    </div>
</div>