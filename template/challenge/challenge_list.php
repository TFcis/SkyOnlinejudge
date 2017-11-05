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
            <form class="form-inline" action="<?=$SkyOJ->uri('chal','list')?>">
                <div class="form-group">
                    <label for="user">Uid</label>
                    <input type="text" class="form-control" pattern="[0-9]\d*" name="uid" placeholder="uid">
                </div>
                <div class="form-group">
                    <label for="pid">Pid</label>
                    <input type="text" class="form-control" pattern="[0-9]\d*" name="pid" placeholder="pid">
                </div>
                <div class="form-group">
                    <label for="verdict">Verdict</label>
                    <select class="form-control" name="result">
                        <option selected value> -- select an option -- </option>
                        <?php foreach(\SKYOJ\RESULTCODE::getConstants() as $key=>$val):?>
                        <option value='<?=$val?>'><?=$key?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Submit</button>
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
                <?php foreach ($_E['template']['challenge_info'] as $row):?>
                    <?php $c=$SkyOJ->User->checkPermission($cl_prob[$row['pid']]);?>
                    <tr>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$row['cid'];?></a></td>
                        <?php $nickname = \SKYOJ\nickname($row['uid']); ?>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=\SKYOJ\html($nickname[$row['uid']])?></a></td>
                        <?php $title = \SKYOJ\html( $c?$cl_prob[$row['pid']]->title:'' )?>
                        <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'],'')?>" title="<?=$title?>">
                            <span class="hidden-xs"><?=$title?></span>
                            <span class="visible-xs-inline"><?=$row['pid']?></span>
                        </a></td>
                        <td><?=$c?\SKYOJ\getresulttexthtml($row['result']):''?></td>
                        <td class="hidden-xs"><?=$c?$row['runtime']:0?></td>
                        <td><?=$c?$row['score']:0?></td>
                        <td><?=$row['timestamp']?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <center>
            <?php Render::renderPagination(
            $_E['template']['challenge_list_pagelist'],
            $_E['SITEROOT'].'index.php/chal/list/%d'.$tmpl['challenge_query'],
            $_E['template']['challenge_list_now']) ?>
            </center>
        </div>
    </div>
</div>