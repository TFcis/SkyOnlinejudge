<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-offset-4 col-lg-4 login_form">
            <center>
                <h3><?php echo $_E['site']['name']; ?><br><small class="login_sub_title">Terms & Conditions</small></h3>
                <div class=".container-fluid">
                
                    <div>
                        <div id="license">
                        <?php Render::renderSingleTemplate('mit_license', 'user'); ?>
                        </div>
                    </div>
                    
                    <div style = "text-align: right">
                        <button type="button" class="btn-grn btn-large" onclick="location.href='<?=$SkyOJ->uri('user','register')?>?accept=accept'">
                        I accept these terms
                        </button>
                        <button type="button" class="btn-red btn-large" onclick="location.href='<?=$_E['SITEROOT']?>'">
                        Deny
                        </button>
                    </div>
                    <br><br>
                </div>
            </center>
        </div>
    </div>
</div>