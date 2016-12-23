<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function()
{
    $('#b_add').tooltip();
})
</script>

<div id="image-bar"></div>
<div class="container">

    <div>
        <div class="page-header">
            <h1>排名賽<small>再來啊</small></h1>
        </div>
        <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 40px"></th>
                <th style="width: 40px"></th>
                <th>NAME</th>
                <th style='width: 140px' class="hidden-xs">
                <?php if ($_G['uid']): ?>
                <!--<button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="" data-original-title="新增記分板" id="b_add" onclick="location.href='rank.php?mod=cbedit'">-->
                    TOOLS
                    <a class="icon-bttn" title="Create New" href="<?=$SkyOJ->uri('contest','new')?>">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                <!--</button>-->
                <?php endif; ?>
                </th>
                <th style="width: 100px">STATUS</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tmpl['contest_info'] as $row): ?>
            <tr style="height: 40px">
                <td style="width: 40px"></td>
                <td><?=$row['cont_id']?></td>
                <td><a href="<?=$SkyOJ->uri('contest','view',$row['cont_id'])?>"><?=\SKYOJ\html($row['title'])?></a></td>
                <td class="hidden-xs">
                    <?php if ($_G['uid']): ?>
                        <a class="icon-bttn" href="<?=$SkyOJ->uri('contest','scoreboard',$row['cont_id'])?>">
                            <span class="glyphicon glyphicon glyphicon-th-list" title="記分板"></span>
                        </a>
                        <!--<span class="icon-bttn glyphicon glyphicon-plus-sign" title="加入"></span>
                        <span class="icon-bttn glyphicon glyphicon-remove" title="離開"></span>-->
                        <?php if (userControl::getpermission($row['owner'])): ?>
                            <a class="icon-bttn" href="<?=$SkyOJ->uri('contest','resolver',$row['cont_id'])?>">
                                <span class="glyphicon glyphicon glyphicon glyphicon-flag" title="開獎機"></span>
                            </a>
                        <!--<span class = "icon-bttn">
                            <span class="glyphicon glyphicon-lock" title="鎖定"></span>
                        </span>-->
                        
                        <!--<<span class="icon-bttn">
                            <span class="glyphicon glyphicon-trash" title="移除"></span>
                        </span>-->
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <span class='jointoggle-on'>
                        <?= \SKYOJ\ContestTeamStateEnum::str( \SKYOJ\Contest::user_regstate_static($_G['uid'],$row['cont_id']) ) ?>
                        
                        <?php if (false&&$row['userstatus']):?>
                            <?php if ($row['userstatusinfo'] === true):?>
                                <span class="AC glyphicon glyphicon-ok"></span>
                                <span>Finish!</span>
                            <?php else:?>
                                <span class="WA glyphicon glyphicon-thumbs-down"></span>
                                <span>完成<?=$row['userstatusinfo']?></span>
                            <?php endif;?>
                        <?php endif;?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    
    <center>
        <?php Render::renderPagination(
        $tmpl['contest_list_pagelist'],
        $SkyOJ->uri('contest','list','%d'),
        $tmpl['contest_list_now']) ?>
    </center>
</div>