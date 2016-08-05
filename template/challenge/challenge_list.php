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
                <td><a href="<?=$Skyoj->uri('challenge','result',$row['id'])?>"><?=$row['id'];?></a></td>
                <?php $row['user'] = (string) $row['user'];  $nickname = nickname($row['user']); ?>
                <td><a href="<?=$Skyoj->uri('user','view',$row['user'])?>"><?=$nickname[$row['user']]?></a></td>
                <td><a href="<?=$Skyoj->uri('problem','view',$row['problem'])?>"><?=$row['problem']; ?></a></td>
                <td><?=$row['result']?></td>
                <td><?=$row['time'];?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <center>
    <?php Render::renderPagination(
    $_E['template']['challenge_list_pagelist'],
    $_E['SITEROOT'].'challenge.php/list/%d',
    $_E['template']['challenge_list_now']) ?>
    </center>
</div>