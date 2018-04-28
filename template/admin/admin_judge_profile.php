<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">

    <div>
        <div class="page-header">
            <h1>Judge Profile</h1>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 180px">編號</th>
                    <th style="width: 180px">名稱</th>
                    <th>Judge</th>
                    <th><?=lang('tools')?>(<a href="#" tmpl="judge_profile/new/">+</a>)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach( $tmpl['judge_profiles'] as $row ): ?>
                <tr>
                    <td><?=$row['id']?></td>
                    <td><?=\SKYOJ\html($row['text'])?></td>
                    <td><?=\SKYOJ\html(\SkyOJ\Judge\JudgeTypeEnum::str($row['judge']))?></td>
                    <td>
                        <a href="#" tmpl="judge_profile/modify/<?=$row['id']?>">修改</a>
                        <a href="#" tmpl="judge_profile/remove/<?=$row['id']?>">刪除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
    </div>
</div>