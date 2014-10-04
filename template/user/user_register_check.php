<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-1" id="signinlogo">
        </div>
        <div class="col-md-4 col-md-offset-1" id="signin">
            <h3><?php echo($_E['site']['name']);?><small>註冊須知</small></h3>
            <div class=".container-fluid">
                <div class="row">
                    <div class="col-md-12" id="license">
                    測試網站不保證資料會保存歐<br>
                    <?php _renderSingleTemplate('user/mit_license'); ?>
                    </div>
                </div>
                <div class="row text-right">
                    <button type="button" class="btn btn-success" onclick="location.href='user.php?mod=register&accept=accept'">同意</button>
                    <button type="button" class="btn btn-danger" onclick="location.href='index.php'">拒絕</button>
                </div>
            </div>
        </div>
    </div>
</div>