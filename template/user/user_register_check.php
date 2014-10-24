<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="container">

        <div class="col-md-4 col-md-offset-1" id="signin">
            <h3><?php echo($_E['site']['name']);?><br><small>Terms & Conditions</small></h3>
            <div class=".container-fluid">
                <div class="row">
                    <div class="col-md-12" id="license">
                    測試網站不保證資料會保存歐<br>
                    <?php Render::renderSingleTemplate('mit_license','user'); ?>
                    </div>
                </div>
                <div class="row text-right">
                    <button type="button" class="btn-grn btn-large" onclick="location.href='user.php?mod=register&accept=accept'">
                    I agree to these terms
                    </button>
                    <button type="button" class="btn-red btn-large" onclick="location.href='index.php'">
                    Deny
                    </button>
                </div>
            </div>
        </div>

</div>