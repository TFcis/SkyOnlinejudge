<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_HIDDEN;
use \SKYOJ\HTML_INPUT_PASSWORD;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_INPUT_BUTTOM;
?>
<script>
var pageid = '<?=$_E['template']['showid']?>' ;
$(document).ready(function()
{
    $("#acct").submit(function(e)
    {
        e.preventDefault();
        var x1 = $("#newpass").val();
        var x2 = $("#renewpass").val();
        if( x1 == '' )
        {
            $("#renewpass").val('');
        }
        else
        {
            if( x1!=x2 )
            {
                $("#btn-show").html('密碼不相等');
                $("#btn-show").css('color','Red');
                return false;
            }
        }
        
        api_submit("<?=$SkyOJ->uri('user','edit','account')?>","#acct","#btn-show",function(){
            setTimeout(function(){location.reload();},500);
        });
        return true;
    });
})
</script>

<div class="container">

    <div class="row">
        <div><h2><?=lang('account')?></h2></div>
        <div class="panel panel-default">
            <div class="panel-heading">Account Information</div>
            <div class="panel-body">
                <p>SOJ ID : <?=$tmpl['showid']?></p>
                <?php if (userControl::isAdmin($tmpl['showid'])):?>
                <p>WOW ADMIN!</p>
                <?php endif; ?>
                <?php if (!empty($_E['template']['acct']['realname'])):?>
                <p>我的名子：<?=htmlentities($_E['template']['acct']['realname'])?></p>
                <?php endif; ?>
                <p>avatar via <a href="https://gravatar.com/" class="btn btn-primary active" target="_blank">gravatar</a></p>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Account Setting</div>
            <div class="panel-body">
                <?php
                Render::renderForm(new FormInfo([
                    'data' => [
                        new HTML_INPUT_HIDDEN(['name' => 'id','value'=>$tmpl['showid']]),
                        new HTML_INPUT_PASSWORD(['name' => 'oldpasswd','required'=>'required','option' => ['help_text' => '舊密碼']]),

                        new HTML_ROW(['html' => '<h3>修改密碼</h3>']),
                        new HTML_INPUT_PASSWORD(['name' => 'newpasswd','id'=>  'newpass','option' => ['help_text' => '新密碼']]),
                        new HTML_INPUT_PASSWORD(['name' => ''         ,'id'=>'renewpass','option' => ['help_text' => '再次確認']]),

                        new HTML_ROW(['html' => '<h3>修改名稱</h3>']),
                        new HTML_INPUT_TEXT(  ['name' => 'nickname','id'=>  'nickname','option' => ['help_text' => '暱稱']]),
                        new HTML_INPUT_TEXT(  ['name' => 'realname','option' => ['help_text' => '真實姓名']]),
                        
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ],
                ]),"acct");
                ?>
            </div><!--account setting panel-body end-->
        </div>
        
    </div>
</div>
