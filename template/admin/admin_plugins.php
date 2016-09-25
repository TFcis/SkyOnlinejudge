<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">

    <div>
        <div class="page-header">
            <h1>Plugins<small>List All Plugins</small></h1>
        </div>
        <?php foreach ($_E['template']['sysplugins'] as $folder => $classes):?>
        <h3><?=$folder?></h3>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 180px">狀態</th>
                    <th style="width: 180px">名稱</th>
                    <th><?=lang('information')?></th>
                    <th><?=lang('tools')?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($classes as $classname => $data): ?>
                <tr>
                    <td>
                        <?php if($data === false): ?>
                            not install
                        <?php else: ?>
                            installed
                        <?php endif; ?>
                    </td>
                    <td><?=htmlentities($classname)?></td>
                    <td><?=htmlentities($classname::DESCRIPTION)?></td>
                    <td>
                        <?php if ($data === false): ?>
                            <a href="#" tmpl="plugins/install/<?=base64_encode($folder)?>/<?=base64_encode($classname)?>">安裝</a>
                        <?php else: ?>
                            <a href="#" tmpl="plugins/uninstall/<?=base64_encode($folder)?>/<?=base64_encode($classname)?>">移除</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <?php endforeach; ?>
    </div>
</div>