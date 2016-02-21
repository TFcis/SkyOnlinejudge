<?php
if(!defined('IN_TEMPLATE'))
{
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
                <form class="form-horizontal" role="form" id="quote">
                    <input type='hidden' name='mod' value='edit'>
                    <input type='hidden' name='page' value='quote'>
                    <input type='hidden' name='id' value='<?=$tmpl['showid']?>'>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Quote</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="quote" placeholder="Quote" value='<?=$tmpl['quote']?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Quote Reference</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="quote_ref" placeholder="Quote Reference" value='<?=$tmpl['quote_ref']?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-success text-right">送出</button>
                            <small><span id="quote-show"></span></small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">Avatar & Background</div>
            <div class="panel-body">
                <p>Avatar via <a href="https://gravatar.com/" class="btn btn-primary active" target="_blank">gravatar</a></p>
                <form class="form-horizontal" role="form" id="avatar">
                    <input type='hidden' name='mod' value='edit'>
                    <input type='hidden' name='page' value='avatar'>
                    <input type='hidden' name='id' value='<?=$tmpl['showid']?>'>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Avatar</label>
                        <div class="col-md-8">
                            <input type="url" class="form-control" name="avatarurl" placeholder="Avatar url" value='<?=$tmpl['avatarurl']?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Background</label>
                        <div class="col-md-8">
                            <input type="url" class="form-control" name="backgroundurl" placeholder="Backgroundurl" value='<?=$tmpl['backgroundurl']?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" class="btn btn-success text-right" disabled="disabled">送出</button>
                            <small><span id="Avatar-show"></span>Comming Soon...</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
