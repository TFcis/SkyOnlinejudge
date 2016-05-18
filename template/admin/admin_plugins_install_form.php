<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <center>
            <h3><?=$tmpl['class']?><br><small class="login_sub_title">安裝設定</small></h3>
        </center>
        <?php Render::renderForm($tmpl['pif_install'],"sid")?>
        <a class="btn btn-danger" href="#" tmpl="plugins/install/<?=base64_encode($tmpl['folder'])?>/<?=base64_encode($tmpl['class'])?>">重新檢查</a>
    </div>
</div>
