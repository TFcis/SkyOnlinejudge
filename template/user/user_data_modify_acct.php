<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#ojacct").submit(function(e)
    {
        e.preventDefault();
        $.post("user.php",
            $("#ojacct").serialize(),
            function(data){
                alert("Data Loaded: " + data);
        });
        return true;
    });
})
</script>
<h1>可用的Online Judge System</h1>
<br>
<div class="container">
    <div class="row">
        <form class="form-horizontal" role="form" id="ojacct">
            <input type='hidden' name='mod' value='edit'>
            <input type='hidden' name='page' value='ojacct'>
            <input type='hidden' name='id' value='<?=$_E['template']['showid']?>'>
            <?php foreach($_E['template']['oj'] as $oj){ ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label"><?=$oj['name']?></label>
                <div class="col-md-5">
                    <input type="text" class="form-control" id="<?=$oj['class']?>" placeholder="<?=$oj['description']?>">
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-md-5">
                    <button type="submit" class="btn btn-success text-right">送出</button>
                </div>
            </div>
        </form>
    </div>
</div>
