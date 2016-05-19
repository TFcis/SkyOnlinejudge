<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <div class="text-center">
            <h1>Oops! 不存在的操作</h1>
            <p><?=@$tmpl['message']?></p>
        </div>
    </div>
</div>  
