<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
$cl_prob = $tmpl['challenge_prob'];
?>
<div id = "image-bar"></div>
<div class="container">
    
	<div class="page-header">
        <h1 class="inline">Challenge<small></small></h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form class="form-inline">
                <div class="form-group">
                    <label for="user">Uid</label>
                    <input type="text" class="form-control" id="user" placeholder="uid">
                </div>
                <div class="form-group">
                    <label for="pid">Pid</label>
                    <input type="text" class="form-control" id="pid" placeholder="pid">
                </div>
                <div class="form-group">
                    <label for="verdict">Verdict</label>
                    <input type="text" class="form-control" id="verdict" placeholder="verdict">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default disabled">Submit</button>
                </div>
            </form>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>使用者</th>
                        <th>題目</th>
                        <th>結果</th>
                        <th class="hidden-xs">使用時間</th>
                        <th>分數</th>
                        <th>上傳時間</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_E['template']['challenge_info'] as $row): ?>
                    <?php if( !\SKYOJ\Problem::hasContentAccess_s($_G['uid'],$cl_prob[$row['pid']]['owner'],$cl_prob[$row['pid']]['content_access']) ) continue; ?>
                    <tr>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$row['cid'];?></a></td>
                        <?php $nickname = \SKYOJ\nickname($row['uid']); ?>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=\SKYOJ\html($nickname[$row['uid']])?></a></td>
                        <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'])?>"><?=\SKYOJ\html(\SKYOJ\Problem::get_title($row['pid']))?></a></td>
                        <td><?=\SKYOJ\getresulttexthtml($row['result'])?></td>
                        <td class="hidden-xs"><?=$row['time']?></td>
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
    </div>
</div>