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
        <table class = "table">
            <thead>
                <tr>
                    <th style = "width: 180px">Installed</th>
                    <th style = "width: 180px" class = "hidden-xs">Class Name</th>
                    <th>Information</th>
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
                    <td></td>
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