<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
$cl_prob = $tmpl['challenge_prob'];
?>
<div id = "image-bar"></div>
<div class="container">
	<div class="page-header">
        <h1>Challenge<small></small></h1>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>使用者</th>
                <th>題目</th>
                <th>結果</th>
                <th>分數</th>
                <th>時間</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['challenge_info'] as $row): ?>
            <?php if( !\SKYOJ\Problem::hasSubmitAccess_s($_G['uid'],$cl_prob[$row['pid']]['owner'],$cl_prob[$row['pid']]['submit_access']) ) continue; ?>
            <tr>
                <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$row['cid'];?></a></td>
                <?php $nickname = \SKYOJ\nickname($row['uid']); ?>
                <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=\SKYOJ\html($nickname[$row['uid']])?></a></td>
                <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'])?>"><?=\SKYOJ\html(\SKYOJ\Problem::get_title($row['pid']))?></a></td>
                <td><?=\SKYOJ\getresulttexthtml($row['result'])?></td>
                <td><?=$row['score']?></td>
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