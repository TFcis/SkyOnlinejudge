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
            <h1>題目列表<small></small></h1>
        </div>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 40px"></th>
                <th style = "width: 40px"></th>
                <th>NAME</th>
                <th style = 'width: 140px' class="hidden-xs">
                <?php if ($_G['uid']): ?>
                <!--<button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="" data-original-title="新增記分板" id="b_add" onclick="location.href='rank.php?mod=cbedit'">-->
                    TOOLS
                    <a class = "icon-bttn" title = "Create New" href="<?=$SkyOJ->uri('problem','new')?>">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                <!--</button>-->
                <?php endif; ?>
                </th>
                <th style = "width: 100px">TAG</th>
                <th style = "width: 180px" class = "hidden-xs">AC rate</th>
                <th style = "width: 180px" class = "hidden-xs">AC rate</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['problem_info'] as $row) :?>
            <tr style = "height: 40px">
                <td>AC</td>
                <td><?=$row['pid'];?></td>
                <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'])?>"><?=htmlspecialchars($row['title']);?></a></td>
                <td class="hidden-xs">
                    <?php if ($_G['uid'] && userControl::getpermission($row['owner'])): ?>
                        <a class="icon-bttn" href="<?=$SkyOJ->uri('problem','modify',$row['pid'])?>">
                            <span class="glyphicon glyphicon-pencil" title="編輯"></span>
                        </a>
                        <span class = "icon-bttn">
                            <span class="glyphicon glyphicon-trash" title="移除"></span>
                        </span>
                    <?php endif; ?>
                </td>
                <td></td>
                <td class="hidden-xs"></td>
                <td></td>
            </tr>
        <?php endforeach;?>
        </tbody>
        
        </table>
        <center>
        <?php Render::renderPagination(
        $_E['template']['problem_list_pagelist'],
        $SkyOJ->uri('problem','list','%d'),
        $_E['template']['problem_list_now']) ?>
        </center>
    </div>
    
    
</div>