<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<ul class="pagination">
    <?php
        $_p = $_E['template']['_pagelist'];
        $_purl = $_E['template']['_pagelist_url'];
        $_now = $_E['template']['_pagelist_now'];
    ?>
    <li><a href="<?=sprintf($_purl, $_p->left($_now))?>">&laquo;</a></li>
    <?php for ($_i = $_p->min($_now); $_i <= $_p->max($_now); $_i++) {
    ?>
        <?php if ($_i == $_now): ?>
            <li class="active"><a href="#"><?=$_i?></a></li>
        <?php else:?>
            <li><a href="<?=sprintf($_purl, $_i)?>"><?=$_i?></a></li>
        <?php endif;
    ?>
    <?php 
}?>
    <li><a href="<?=sprintf($_purl, $_p->right($_now))?>">&raquo;</a></li>
</ul>
