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
                <?php if( $tmpl['state']==1 ) : ?>
            <a class = "icon-bttn" onclick="build_cb_data('all')">
                <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
            </a>
                <?php endif; ?>
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
    <?php if($tmpl['state']==1): ?>
        <?php Render::renderSingleTemplate('rank_statboard_cmtable','rank'); ?>
    <?php elseif($tmpl['state']==2): ?>
        <?php if($tmpl['rank_cb_fzboard']): ?>
            <?php Render::rendercachehtml($tmpl['rank_cb_fzboard']); ?>
        <?php else:?>
            <?php Render::renderSingleTemplate('nonedefined'); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php Render::renderSingleTemplate('nonedefined'); ?>
    <?php endif; ?>
    <!--end table-->
    
    <hr>
    <div class="row">
        <h1>Announcement </h1>
        <div class="well" style="background-color:#565656">
            <?php if( empty($tmpl['announce']) ): ?>No Announcement...
            <?php else: ?><?=$tmpl['announce']?><?php endif;?>
        </div>
    </div>
    

</div>

