<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<script>
    function build_cb_data(user,scallid)
    {
        $("#infobox").html("Rebuilding...");
        scallid = typeof scallid !== 'undefined' ? scallid : '';
        $.get(
            "rank.php",
            {
                mod : 'cbfetch',
                id  : '<?=$_E['template']['id']?>',
                scallid : scallid,
                user : user
            },
            function(res){
                if(res.status === 'error')
                {
                   $("#infobox").html(res.data);
                }
                else if(res.status === 'SUCC')
                {
                    $("#infobox").html("YES");
                    setTimeout(function(){location.reload();}, 500);
                }
            },"json"
        );
    }
    $(document).ready(function()
    {
        //$("#display").html("SUBMIT...");
        <?php if($_E['template']['cbrebuild']):?>
        build_cb_data('all','<?=$_E['template']['cbrebuildkey']?>');
        <?php endif;?>
        //$(".problemname").popover({trigger : 'hover'});
    })
</script>
<div id = "image-bar"></div>
<div class="container">
    <div>
        <div class="page-header">
            <h1><?=htmlspecialchars($_E['template']['title'])?> <small>Statistics
            <?php if(userControl::getpermission($_E['template']['owner'])): ?>
            <a class = "icon-bttn" href='rank.php?mod=cbedit&id=<?=$_E['template']['id'];?>'>
                <span class="pointer glyphicon glyphicon-pencil"  title="編輯"></span>
            </a>
            <a class = "icon-bttn" onclick="build_cb_data('all')">
                <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
            </a>
            <?php endif; ?>
                </small>
            </h1>
            <div class='container-fluid'>
                <div class="row">
                    <div class="col-xs-4 col-md-4 text-left">
                        <a href="rank.php?mod=commonboard&id=<?=$_E['template']['leftid']?>" class="btn btn-primary btn-sm active" <?php if(!$_E['template']['leftid'])echo('disabled="disabled"');?>>
                        <span class="glyphicon glyphicon-arrow-left"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-center">
                        <a href="rank.php?mod=list&page=<?=$_E['template']['homeid']?>" class="btn btn-primary btn-sm active">
                        <span class="glyphicon glyphicon-home"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-right">
                        <a href="rank.php?mod=commonboard&id=<?=$_E['template']['rightid']?>" class="btn btn-primary btn-sm active" <?php if(!$_E['template']['rightid'])echo('disabled="disabled"');?>>
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!--table-->
    <div>
        <div>
            <table id="cbtable">
                <thead>
                    <tr>
                        <th style="padding: 4px;width: 40px;left:0px;position: absolute;"></th>
                        <th style="padding: 4px;width: 120px;left:40px;position: absolute;"><span id="infobox"></span></th>
                        <th class="text-center" style="padding: 4px;width: 40px;">rate</th>
                        <?php foreach($_E['template']['plist'] as $prob ){?>
                            <th class="text-center" style="padding: 4px;width: 40px;">
                                <div class="problemname"><?=$prob['show']?></div>
                            </th>
                        <?php }?>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody sytle="white-space: nowrap;">
                    <?php foreach($_E['template']['user'] as $uid){?>
                    <tr>
                        <td style="left:0px;position: absolute;">
                            <?php if(userControl::getpermission($_E['template']['owner']) || $uid == $_G['uid']): ?>
                            <a class = "icon-bttn" onclick="build_cb_data('<?=$uid?>')">
                                <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
                            </a>
                            <?php endif;?>
                        </td>
                        <td class="text-right" style="left:40px;position:absolute;">
                            <div class="nickname">
                                <a style="color:white;" href=<?="user.php?mod=view&id=$uid"?>>
                                    <?=$_E['nickname'][$uid]?>
                                </a>
                            </div>
                        </td>
						<?php $AC_count = $_E['template']['userdetail'][$uid]['statistics']['90']; ?>
                        <td class="text-right"><?=$AC_count?>/<?=round($AC_count/count($_E['template']['plist'])*100.0)?>%</td>
<?php foreach($_E['template']['plist'] as $prob ){?><td class = "text-center <?=$_E['template']['s'][$uid][$prob['name']]?>">●</td><?php }?>
                        <td>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div style = "color: #666666; text-align: right; padding-right: 20px">Lastest update: <?=$_E['template']['buildtime'] ?></div>
    
    <div class="row">
        <h1>DEBUG</h1>
        <p><?=$_E['template']['dbg']?></p>
    </div>
    

</div>

<script>

</script>

