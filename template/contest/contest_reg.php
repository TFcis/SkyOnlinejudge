<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-offset-4 col-lg-4 login_form">
            <center>
                <h3><?=\SKYOJ\html($tmpl['contest']->title)?><br><small class="login_sub_title">Terms & Conditions</small></h3>
                <div class=".container-fluid">
                
                    <div>
                        <div id="license">
                        <?php Render::renderSingleTemplate('contest_license'); ?>
                        </div>
                    </div>
                    <div style = "text-align: right">
                        <button type="button" class="btn-grn btn-large" onclick="location.href='<?=$SkyOJ->uri('contest','register',$tmpl['contest']->cont_id())?>'">
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