<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <center>
            <h3>選擇 OJCapture</h3>
        </center>
    </div>
    <hr>
    <div class="row">
        <?php foreach( $tmpl['ojcaptures'] as $text => $id ):?>
        <div> <a tmpl='ojcapture_profile/new/<?=$id?>'><?=\SKYOJ\html($text)?></a> </div>
        <?php endforeach;?>
    </div>
</div>
