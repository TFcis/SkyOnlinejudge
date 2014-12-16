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
                id  : '<?=$tmpl['id']?>',
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
    var viededrate = 'score';
    function change_rate()
    {
        if( viededrate == 'rate' )
        {
            $(".ac_rate").hide();
            $(".score").show();
            viededrate = 'score';
        }
        else
        {
            $(".score").hide();
            $(".ac_rate").show();
            viededrate = 'rate';
        }
        $("#svchange").html(viededrate);
    }
    $(document).ready(function()
    {
        //$("#display").html("SUBMIT...");
        <?php if($tmpl['cbrebuild']):?>
        build_cb_data('all','<?=$tmpl['cbrebuildkey']?>');
        <?php endif;?>
        //$(".problemname").popover({trigger : 'hover'});
    })
</script>
<div id = "image-bar"></div>
<div class="container">
    <div>
        <div class="page-header">
            <h1><?=htmlspecialchars($tmpl['title'])?> <small>Statistics
            <?php if(userControl::getpermission($tmpl['owner'])): ?>
            <a class = "icon-bttn" href='rank.php?mod=cbedit&id=<?=$tmpl['id'];?>'>
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
                        <a href="rank.php?mod=commonboard&id=<?=$tmpl['leftid']?>" class="btn btn-primary btn-sm active" <?php if(!$tmpl['leftid'])echo('disabled="disabled"');?>>
                        <span class="glyphicon glyphicon-arrow-left"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-center">
                        <a href="rank.php?mod=list&page=<?=$tmpl['homeid']?>" class="btn btn-primary btn-sm active">
                        <span class="glyphicon glyphicon-home"></span>
                        </a>
                    </div>
                    <div class="col-xs-4 col-md-4 text-right">
                        <a href="rank.php?mod=commonboard&id=<?=$tmpl['rightid']?>" class="btn btn-primary btn-sm active" <?php if(!$tmpl['rightid'])echo('disabled="disabled"');?>>
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
                        <th class="text-center" style="padding: 4px;width: 50px;"><a onclick="change_rate()" id="svchange" title="Change rate/source">score<span></th>
                        <?php foreach($tmpl['plist'] as $prob ){?>
                            <th class="text-center" style="padding: 4px;width: 40px;">
                                <div class="problemname"><?=$prob['show']?></div>
                            </th>
                        <?php }?>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody sytle="white-space: nowrap;">
                    <?php foreach($tmpl['user'] as $uid){?>
                    <tr>
                        <td style="left:0px;position: absolute;">
                            <?php if(userControl::getpermission($tmpl['owner']) || $uid == $_G['uid']): ?>
                            <a class = "icon-bttn" onclick="build_cb_data('<?=$uid?>')">
                                <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
                            </a>
                            <?php endif;?>
                        </td>
                        <td class="text-right" style="left:40px;position:absolute;">
                            <div class="nickname">
                                <a style="color:white;" href=<?="user.php?mod=view&id=$uid"?>><?=$_E['nickname'][$uid]?></a>
                            </div>
                        </td>
						<?php $AC_count = $tmpl['userdetail'][$uid]['statistics']['90']; ?>
                        <td class="text-right">
                        <span class="score" onclick="change_rate()"><?=$AC_count?>AC</span>
                        <span class="ac_rate" style="display:none" onclick="change_rate()"><?=round($AC_count/count($tmpl['plist'])*100.0)?>%</span>

                        </td>
<?php foreach($tmpl['plist'] as $prob ){ 
    $vid  = $tmpl['s'][$uid][$prob['name']]["vid"]; 
    $chal = $tmpl['s'][$uid][$prob['name']]["challink"];
    
    ?><td class = "text-center <?=$vid?>"><?php
    if( $chal == '' )
    {
        ?>●<?php
    }
    else
    {
        ?><span onclick = "javascript:window.open('<?=$chal?>')" target="_blank" style="cursor: pointer;">●</span><?php
    }
    
    ?></td><?php
}?>
                        <td>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div style = "color: #666666; text-align: right; padding-right: 20px">Lastest update: <?=$tmpl['buildtime'] ?></div>
    <hr>
    <div class="row">
        <h1>Announcement </h1>
        <div class="well" style="background-color:#565656">
            <?php if( empty($tmpl['announce']) ): ?>No Announcement...
            <?php else: ?><?=$tmpl['announce']?><?php endif;?>
        </div>
    </div>
    

</div>

