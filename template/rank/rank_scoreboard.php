<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
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

    })
</script>
<div id="image-bar"></div>
<div class="container">
    <div>
        <div class="page-header">
            <h1><?=\SKYOJ\html($tmpl['sb']->GetTitle())?> <small>Statistics
            <?php if (userControl::getpermission($tmpl['sb']->owner())): ?>
            <a class = "icon-bttn" href='<?=$SkyOJ->uri('rank','modify',$tmpl['sb']->sb_id())?>'>
                <span class="pointer glyphicon glyphicon-pencil"  title="編輯"></span>
            </a>
                <?php if(false): ?>
            <a class = "icon-bttn" onclick="build_cb_data('all')">
                <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
            </a>
                <?php endif; ?>
            <?php endif; ?>
                </small>
            </h1>
        </div>
    </div>
    <!--table-->
    <?php if (true): ?>
        <?php Render::renderSingleTemplate('rank_scoreboard_basic', 'rank'); ?>
    <?php else: ?>
        <?php Render::renderSingleTemplate('nonedefined'); ?>
    <?php endif; ?>
    <!--end table-->
    <hr>
    <div class="row">
        <h1>Announcement</h1>
        <div class="well" style="background-color:#565656">
            <?php if (empty($tmpl['sb']->GetAnnounce())): ?>No Announcement...
            <?php else: ?><?=$tmpl['sb']->GetAnnounce()?><?php endif; ?>
        </div>
    </div>
    <div style ="color: #666666; text-align: right; padding-right: 20px"><?=$tmpl['sb']->GetStart()?>~<?=$tmpl['sb']->GetEnd()?></div>
</div>

