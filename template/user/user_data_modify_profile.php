<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#quote").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$_E['SITEROOT']?>user.php/edit","#quote","#quote-show",function(){
            setTimeout(function(){
                location.href="<?=$_E['SITEROOT']?>user.php/view/<?=$tmpl['showid']?>/setting/profile";
            }, 500);
        });
        /*$.post("<?=$_E['SITEROOT']?>user.php/edit",
            $("#quote").serialize(),
            function(res){
                if(res.status == 'error')
                {
                    $("#quote-show").html(res.data);
                    $("#quote-show").css('color','Red');
                }
                else
                {
                    $("#quote-show").css('color','Lime');
                    $("#quote-show").html('Success!');
                    setTimeout(function(){
                        location.href="<?=$_E['SITEROOT']?>user.php/view/<?=$tmpl['showid']?>/setting/profile";
                    }, 500);
                }
        },"json").error(function(e){
            console.log(e);
        });*/
        return true;
    });
})
</script>

<div class="container">

    <div class="row">
        <div><h2>Account</h2></div>
        <div class="panel panel-default">
            <div class="panel-heading">Quote</div>
            <div class="panel-body">
                <?php
                Render::renderForm(new FormInfo([
                    'data' => [
                        new HTML_INPUT_HIDDEN(['name' => 'mod' ,'value'=>'edit']),
                        new HTML_INPUT_HIDDEN(['name' => 'page','value'=>'quote']),
                        new HTML_INPUT_HIDDEN(['name' => 'id','value'=>$tmpl['showid']]),
                        new HTML_INPUT_TEXT(  ['name' => 'quote' ,'value'=>$tmpl['quote'] ,'option' => ['help_text' => 'Quote']]),
                        new HTML_INPUT_TEXT(  ['name' => 'quote_ref' ,'value'=>$tmpl['quote_ref'] ,'option' => ['help_text' => 'Quote Reference']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出']),
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
