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
        <?php foreach ($_E['template']['sysplugins'] as $folder => $classes) {
    ?>
        <h3><?=$folder?></h3>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 180px">Installed</th>
                    <th style="width: 180px">Class Name</th>
                    <th><?=lang('information')?></th>
                    <th><?=lang('tools')?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($classes as $classname => $data) {
    ?>
                <tr>
                    <td>
                        <?php if ($data === false): ?>
                            not install
                        <?php else: ?>
                            installed
                        <?php endif;
    ?>
                    </td>
                    <td><?=htmlentities($classname)?></td>
                    <td><?=htmlentities($classname::DESCRIPTION)?></td>
                    <td>
                        <?php if ($data === false): ?>
                            <a href="#" tmpl="plugins/install/<?=base64_encode($folder)?>/<?=base64_encode($classname)?>">安裝</a>
                        <?php else: ?>
                            移除
                        <?php endif;
    ?>
                    </td>
                </tr>
            <?php 
}
    ?>
            </tbody>
        </table>
        <br>
        <?php 
}?>
    </div>
</div>