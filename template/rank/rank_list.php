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

<div id = "image-bar"></div>
<div class="container">

    <div>
        <div class="page-header">
            <h1>解題統計 <small>網羅各大OJ資訊</small></h1>
        </div>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 40px"></th>
                <th>NAME</th>
                <th style = 'width: 140px' class="hidden-xs">
                <?php if ($_G['uid']): ?>
                <!--<button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="" data-original-title="新增記分板" id="b_add" onclick="location.href='rank.php?mod=cbedit'">-->
                    TOOLS
                    <a class = "icon-bttn" title = "Create New" href="<?=$SkyOJ->uri('rank','new')?>">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                <!--</button>-->
                <?php endif; ?>
                </th>
                <th style = "width: 100px">STATUS</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tmpl['scoreboard_info'] as $row): ?>
            <tr style = "height: 40px">
                <td><?=$row['sb_id']?></td>
                <td><a href="<?=$SkyOJ->uri('rank','commonboard',$row['sb_id'])?>"><?=\SKYOJ\html($row['name'])?></a></td>
                <td class="hidden-xs">
                    <?php if ($_G['uid']): ?>
                        <!--<span class="icon-bttn glyphicon glyphicon-plus-sign" title="加入"></span>
                        <span class="icon-bttn glyphicon glyphicon-remove" title="離開"></span>-->
                        <?php if (userControl::getpermission($row['owner'])): ?>
                        <a class="icon-bttn" href="<?=$SkyOJ->uri('rank','sbedit',$row['sb_id'])?>">
                            <span class="glyphicon glyphicon-pencil" title="編輯"></span>
                        </a>
                        <!--<span class = "icon-bttn">
                            <span class="glyphicon glyphicon-lock" title="鎖定"></span>
                        </span>-->
                        
                        <span class = "icon-bttn">
                            <span class="glyphicon glyphicon-trash" title="移除"></span>
                        </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <span class = 'jointoggle-on'>
                        <?php if ($row['userstatus']):?>
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
        $tmpl['scoreboard_list_pagelist'],
        $SkyOJ->uri('rank','list','%d'),
        $tmpl['scoreboard_list_now']) ?>
    </center>
</div>