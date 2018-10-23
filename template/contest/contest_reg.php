<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#register").click(function(e)
    {
        $('#registerform').modal('show');
    });
});
</script>
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
                        <?php if($_E['template']['needpassword']===true):?>
                        <button type="button" class="btn-grn btn-large" id="register">
                        I accept these terms
                        </button>
                        <?php else:?>
                        <button type="button" class="btn-grn btn-large" onclick="location.href='<?=$SkyOJ->uri('contest','register',$tmpl['contest']->cont_id())?>'">
                        I accept these terms
                        </button>
                        <?php endif;?>
                        <button type="button" class="btn-red btn-large" onclick="location.href='<?=$_E['SITEROOT']?>'">
                        Deny
                        </button>
                    </div>
                    <br><br>
                </div>
            </center>
        </div>
    </div>
    <div class="modal fade" id="registerform" tabindex="-1" role="dialog" aria-labelledby="registerform">
        <div class="modal-dialog" role="document">
            <form class="form-horizontal" action="<?=$SkyOJ->uri('contest','register',$tmpl['contest']->cont_id())?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="registerpassword" style="color:black">請輸入註冊密碼</h4>
                    </div>
                    <div class="modal-body">
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="modal-footer">
                        <small><span id='admin-check-info'></span></small>
                        <button type="submit" class="btn btn-primary" id="submit">送出</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>