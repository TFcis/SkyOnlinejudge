<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<table id="cbtable">
    <thead>
        <tr>
            <th style="padding: 4px;width: 40px;left:0px;position: absolute;"></th>
            <th style="padding: 4px;width: 120px;left:40px;position: absolute;"><span id="infobox"></span></th>
            <th class="text-center" style="padding: 4px;width: 50px;"><a onclick="change_rate()" id="svchange" title="Change rate/source">Score<span></th>
            <?php foreach( $tmpl['sb']->GetProblems() as $prob ):?>
                <th class="text-center" style="padding: 4px;width: 40px;">
                    <div class="problmname" title="<?=\SKYOJ\html($tmpl['sb']->problem_title($prob['problem']))?>"><a href='<?=$SkyOJ->uri('problem','view',$prob['problem'])?>'><?=$prob['problem']?></a></div>
                </th>
            <?php endforeach;?>
            <th></th>
        </tr>
    </thead>
    <tbody sytle="white-space: nowrap;">
        <?php foreach ($tmpl['sb']->GetSortedUsers() as $uid): ?>
        <tr>
            <td style="left:0px;position: absolute;">
                <?php if (false): ?>
                <a class = "icon-bttn" onclick="build_cb_data('<?=$uid?>')">
                    <span class="pointer glyphicon glyphicon-refresh"  title="重新擷取"></span>
                </a>
                <?php endif;?>
            </td>
            <td class="text-right" style="left:40px;position:absolute;">
                <div class="nickname">
                    <a style="color:white;" href='<?=$SkyOJ->uri('user','view',$uid)?>'><?=\SKYOJ\html($_E['nickname'][$uid])?></a>
                </div>
            </td>
    		<?php $AC_count = 0;$score = 0; 
                foreach( $tmpl['tsb'][$uid] as $row )
                {
                    $score += $row[1];
                    if( $row[0] == \SKYOJ\RESULTCODE::AC  )
                        $AC_count++;
                }
            ?>
            <td class="text-right" onclick="change_rate()">
                <span swtab="score" ><?=$score?></span>
                <span swtab="ac_num"  style="display:none"><?=$AC_count?>AC</span>
                <span swtab="ac_rate" style="display:none"><?=round($AC_count / count($tmpl['sb']->GetProblems()) * 100.0)?>%</span>
            </td>
<?php foreach( $tmpl['sb']->GetProblems() as $prob ):?><?php
    $vid = $tmpl['tsb'][$uid][$prob['problem']][0];
    $chal = '';
    $vid = ($vid == \SKYOJ\RESULTCODE::WAIT ||  $vid == \SKYOJ\RESULTCODE::JUDGING) ? 'NO' : \SKYOJ\getresulttext($vid);
?><td class="text-center <?=$vid?>" style="width:50px;font-size:20px;"><?php
    if ($chal == '') {
        ?>●<?php

    } else {
        ?><span onclick = "javascript:window.open('<?=$chal?>')" target="_blank" style="cursor: pointer;">●</span><?php
    }

    ?></td>
<?php endforeach;?>
            <td>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div style = "color: #666666; text-align: right; padding-right: 20px"></div>