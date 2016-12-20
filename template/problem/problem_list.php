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
        <table class = "table table-striped">
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
                <th>Submit AC rate</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['problem_info'] as $row) :?>
            <?php if( !\SKYOJ\Problem::hasContentAccess_s($_G['uid'],$row['owner'],$row['content_access'],$row['pid'])&&
                      !\SKYOJ\Problem::hasSubmitAccess_s($_G['uid'],$row['owner'],$row['submit_access'],$row['pid']) ) continue; ?>
            <tr style = "height: 40px">
                <td>
                    <?php if( $_G['uid'] ):?>
                        <?php $d = \SKYOJ\Problem\UserProblemState($row['pid'],$_G['uid']) ?>
                        <?php if( $d == \SKYOJ\RESULTCODE::AC ): ?>
                            <span class="AC glyphicon glyphicon-ok"></span>
                        <?php elseif( $d > \SKYOJ\RESULTCODE::AC ): ?>
                            <span class="WA glyphicon glyphicon-thumbs-down"></span>
                        <?php endif; ?>
                    <?php endif;?>
                </td>
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
                <?php
                    $all = \SKYOJ\Problem\ProblemSubmitNum($row['pid']);
                    $ac  = \SKYOJ\Problem\ProblemStateNum($row['pid'],\SKYOJ\RESULTCODE::AC);
                    $rate = sprintf("%.2f",($all==0&&$ac==0)?0:$ac*100/$all,2);
                ?>
                <td><?=$rate?>% (<?=$ac?>/<?=$all?>)</td>
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