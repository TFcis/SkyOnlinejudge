<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <center>
            <h3>選擇 Judge</h3>
        </center>
    </div>
    <hr>
    <div class="row">
        <?php foreach( $tmpl['judges'] as $text => $id ):?>
        <div> <a tmpl='judge_profile/new/<?=$id?>'><?=\SKYOJ\html($text)?></a> </div>
        <?php endforeach;?>
    </div>
</div>
