<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<?php Render::renderSingleTemplate('admin_plugins', 'admin'); ?>
<br>
<?php Render::renderSingleTemplate('admin_log', 'admin'); ?>
