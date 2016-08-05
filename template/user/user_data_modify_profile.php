<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_HIDDEN;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_BUTTOM;
?>
<script>
$(document).ready(function()
{
    $("#quote").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('user','edit','quote')?>","#quote","#btn-show",function(){
            setTimeout(function(){
                location.href="<?=$SkyOJ->uri('user','view',$tmpl['showid'],'setting','profile')?>";
            }, 500);
        });
        return true;
    });
})
</script>

<div class="container">

    <div class="row">
        <div><h2>Profile</h2></div>
        <div class="panel panel-default">
            <div class="panel-heading">Quote</div>
            <div class="panel-body">
                <?php
                Render::renderForm(new FormInfo([
                    'data' => [
                        new HTML_INPUT_HIDDEN(['name' => 'id','value'=>$tmpl['showid']]),
                        new HTML_INPUT_TEXT(  ['name' => 'quote' ,'value'=>$tmpl['quote'] ,'option' => ['help_text' => 'Quote']]),
                        new HTML_INPUT_TEXT(  ['name' => 'quote_ref' ,'value'=>$tmpl['quote_ref'] ,'option' => ['help_text' => 'Quote Reference']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ],
                ]),"quote");
                ?>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Avatar & Background</div>
            <div class="panel-body">
                <p>Avatar via <a href="https://gravatar.com/" class="btn btn-primary active" target="_blank">gravatar</a></p>
                <?php
                Render::renderForm(new FormInfo([
                    'data' => [
                        new HTML_INPUT_HIDDEN(['name' => 'mod' ,'value'=>'edit']),
                        new HTML_INPUT_HIDDEN(['name' => 'page','value'=>'avatar']),
                        new HTML_INPUT_HIDDEN(['name' => 'id','value'=>$tmpl['showid']]),
                        new HTML_INPUT_TEXT(  ['name' => '' ,'value'=>$tmpl['avatarurl'],'placeholder'=>'Avatar url' ,'option' => ['help_text' => 'Avatar']]),
                        new HTML_INPUT_TEXT(  ['name' => '' ,'value'=>$tmpl['backgroundurl'] ,'option' => ['help_text' => 'Background']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出Comming Soon...','disabled'=>'disabled']),
                    ],
                ]),"avatar");
                ?>
            </div>
        </div>
        
    </div>
</div>
