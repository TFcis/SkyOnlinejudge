<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $("#board").submit(function(e)
    {
        e.preventDefault();
        $.post("rank.php",
            $("#board").serialize(),
            function(data){
                alert("Data Loaded: " + data);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
          <h1>編輯記分板 <small><?=$_E['template']['title']?></small></h1>
      </div>
    </div>
    <div class="row">
    <form class="form-horizontal" role="form" id="board" >
            <input type="hidden" name="mod" value="edit">
            <input type="hidden" name="id" value="<?=$_E['template']['form']['id']?>">
            <div class="form-group">
                <label class="col-md-2 control-label">名稱</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="name" placeholder="Board Name" value="<?=$_E['template']['form']['name']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">登記ID</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="userlist" placeholder="Account ID" value="<?=$_E['template']['form']['userlist']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">題目列表</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="problems" placeholder="Problems" value="<?=$_E['template']['form']['problems']?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-md-5">
                    <button type="submit" class="btn btn-success text-right">送出</button>
                </div>
            </div>
        </form>
    </div>
</div>