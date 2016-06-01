<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    
    <div>
        <div class="page-header">
            <h1>Plugins<small>安裝失敗</small></h1>
        </div>
        <h4>安裝<?=htmlentities($tmpl['folder'].'/'.$tmpl['class'])?>插件時缺失的系統函數</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>編號</th>
                    <th>函數名</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; foreach ($tmpl['fail_func'] as $func) {
    ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?=htmlentities($func)?></td>
                </tr>
            <?php 
}?>
            </tbody>
        </table>
        <br>
        <a class="btn btn-danger" href="#" tmpl="plugins/install/<?=base64_encode($tmpl['folder'])?>/<?=base64_encode($tmpl['class'])?>">重新檢查</a>
        <a class="btn btn-default" href="#" tmpl="plugins/list/?folder=<?=urlencode($tmpl['folder'])?>">返回列表</a>
    </div>
</div>