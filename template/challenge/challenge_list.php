<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div id = "image-bar"></div>
<div class="container">
	<div class="page-header">
        <h1>Challenge<small></small></h1>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>使用者</th>
                <th>題目</th>
                <th>結果</th>
                <th>時間</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['challenge_info'] as $row): ?>
            <tr>
                <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$row['cid'];?></a></td>
                <?php $nickname = \SKYOJ\nickname($row['uid']); ?>
                <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$nickname[$row['uid']]?></a></td>
                <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'])?>"><?=\SKYOJ\Problem::get_title($row['pid'])?></a></td>
                <td><?=\SKYOJ\getresulttexthtml($row['result'])?></td>
                <td><?=$row['timestamp'];?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <center>
    <?php Render::renderPagination(
    $_E['template']['challenge_list_pagelist'],
    $_E['SITEROOT'].'index.php/chal/list/%d',
    $_E['template']['challenge_list_now']) ?>
    </center>
</div>