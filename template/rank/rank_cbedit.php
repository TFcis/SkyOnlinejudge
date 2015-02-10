<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector:'#announcement',
        plugins :[
            "advlist autolink lists link charmap preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime nonbreaking table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ]
    });
</script>
<script>
$(document).ready(function()
{
    pageid = <?=$tmpl['form']['id']?>;
    $( "[adv-act]" ).click(function(){reqmode($(this).attr('adv-act'));});
    function reqmode(mode)
    {
        $.post("rank.php",{
            mod : 'edit',
            page: 'cb'+mode,
            id  : pageid,
        },function(res){
           alert(res); 
        });
    }
    $("#board").submit(function(e)
    {
        $("#display").html("SUBMIT...");
        e.preventDefault();
        $("#announce").val(tinymce.activeEditor.getContent());
        $.post("rank.php",
            $("#board").serialize(),
            function(res){alert(res);
                /*if(res.status === 'error')
                {
                   $("#display").html(res.data);
                }
                else if(res.status === 'SUCC')
                {
                    $("#display").html("YES");
                    setTimeout(function(){location.href="rank.php?mod=cbedit&id="+res.data;}, 500);
                }*/
        }/*,"json"*/);
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
          <h1>編輯記分板 <small><?=htmlspecialchars($tmpl['title'])?>
          <?php if($_E['template']['form']['id']):?>
            <a class = "icon-bttn" href = "rank.php?mod=commonboard&id=<?=$tmpl['form']['id']?>">
                <span class="pointer glyphicon glyphicon-arrow-left" title="回到記分板"></span>
            </a>
          <?php endif;?>
          </small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <form class="form-horizontal" role="form" id="board" >
                <input type="hidden" name="mod" value="edit">
                <input type="hidden" name="page" value="cbedit">
                <input type="hidden" name="id" value="<?=$tmpl['form']['id']?>">
                <input type="hidden" name="announce" id="announce" value="">
                <div class="form-group">
                    <label class="col-md-3 control-label">名稱</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="name" placeholder="Board Name" value="<?=$tmpl['form']['name']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">登記ID</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="userlist" placeholder="Account ID" value="<?=$tmpl['form']['userlist']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">題目列表</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="problems" placeholder="Problems" value="<?=$tmpl['form']['problems']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">公告</label>
                    <div class="col-md-9">
                        <textarea id="announcement"><?=$tmpl['form']['announce']?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-md-9">
                        <button type="submit" class="btn btn-success text-right">送出</button>
                        <span id="display"></span>
                    </div>
                </div>
            </form>
        </div><!--Main end-->
        <div class="col-lg-4">
            <h1>Advance</h1>
            <?php if($tmpl['form']['id'] != 0): ?>
                <p>
                    <buttom class="btn btn-primary" adv-act="freeze">Freeze</buttom>
                    凍結記分板 <small><span id="adv-act-freeze">重建並鎖定</span></small>
                </p>
                <?php if($tmpl['form']['state'] != 0): ?>
                <p>
                    <buttom class="btn btn-danger" adv-act="close">Close</buttom>
                    關閉記分板 <small><span id="adv-act-close">關閉記分板</span></small>
                </p>
                <?php endif; ?>
                <?php if($tmpl['form']['state'] != 1): ?>
                <p>
                    <buttom class="btn btn-success" adv-act="open">Open</buttom>
                    開啟記分板 <small><span id="adv-act-open">開啟記分板</span></small>
                </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <br>
     <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table  table-bordered">
                <thead>
                    <tr>
                        <th>插件名稱</th>
                        <th>作者</th>
                        <th>版本</th>
                        <th>描述</th>
                        <th>格式</th>
                    </tr>
                </thead>
                </tbody>
                    <?php foreach($_E['template']['rank_site'] as $site => $data){?>
                    <tr>
                        <td><?=$data['name']?></td>
                        <td><?=$data['author']?></td>
                        <td><?=$data['version']?></td>
                        <td><?=$data['desc']?></td>
                        <td><?=$data['format']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>