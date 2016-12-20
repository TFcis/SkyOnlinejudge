<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<style>
.first_solved, .solvedFirst {
    background-color: #218540 !important;
    color: white !important;
}
.solved, .yes {
 background-color: #5EC583 !important;
}
.attempt, .attempted, .no {
  background-color: #E23F46 !important;
}
</style>
<div class="container">
    <div class="row">
        <table class="table">
            <thead>
                <tr class="score_head">
                    <th>#</th>
                    <th>TEAM</th>
                    <th>SOL.</th>
                    <th>TIME</th>
                    <?php foreach($tmpl['contest']->get_all_problems_info() as $row):?>
                        <th><?=\SKYOJ\html($row->ptag)?></th>
                    <?php endforeach;?>
                    <th>ALL/SOL</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank=1;$last=null; ?>
                <?php foreach($tmpl['user'] as $row):?>
                    <?php if( isset($last) && \SKYOJ\Contest\UserBlock::acm_cmp($last,$row)!=0){$rank++;} ?>
                    <?php $last=$row;$nickname=\SKYOJ\nickname($row->uid); ?>
                    <tr>
                        <td><?=$rank?></td>
                        <td><?=\SKYOJ\html($nickname[$row->uid])?></td>
                        <td><?=$row->ac?></td>
                        <td><?=$row->ac_time?></td>
                        <?php foreach($tmpl['contest']->get_all_problems_info() as $prob):?>
                            <?php 
                                $sb=$tmpl['scoreboard'][$row->uid][$prob->pid];
                                $cls = '';
                                if($sb->firstblood)$cls="solvedFirst";
                                else if($sb->is_ac)$cls="solved";
                                else if($sb->try_times)$cls="attempt";
                            ?>
                            <td class="<?=$cls?>">
                                <?=$sb->try_times?>/<?=$sb->is_ac?$sb->ac_time:"--"?>
                            </td>
                        <?php endforeach;?>
                        <td><?=$row->total_submit?>/<?=$row->ac?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>