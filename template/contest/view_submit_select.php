<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>選擇要傳送的題目</h3>
            <?php foreach($tmpl['contest']->get_all_problems_info() as $prob):?>
                <div class="col-md-12" navpage='prob_<?=\SKYOJ\html($prob->ptag)?>'>
                    <a href="#" tmpl="submit/<?=$prob->pid?>"><?=\SKYOJ\html($prob->ptag.', '.\SKYOJ\Problem::get_title($prob->pid))?></a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <br>
</div>