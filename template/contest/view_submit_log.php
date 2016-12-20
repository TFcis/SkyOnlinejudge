<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
<?php foreach ($tmpl['challenge_info'] as $data): ?>
    <?php if( $data['result'] < \SKYOJ\RESULTCODE::AC ):?>
        updateJudgeVerdict("<?=$SkyOJ->uri('chal','api','waitjudge')?>",<?=$data['cid']?>,function(cid,res){
            $("#cid-"+cid).html(res.data.verdict);
            $("#update_info").show();
        });
    <?php endif?>
<?php endforeach;?>
</script>
<div class="container">
    <div class="row">
        <h4 id="update_info" hidden="hidden">You should renew page to see full detail</h4>
    <div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>使用者</th>
                        <th>題目</th>
                        <th>結果</th>
                        <th class="hidden-xs">使用時間</th>
                        <th>分數</th>
                        <th>上傳時間</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_E['template']['challenge_info'] as $row): ?>
                    <tr>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=$row['cid'];?></a></td>
                        <?php $nickname = \SKYOJ\nickname($row['uid']); ?>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row['cid'])?>"><?=\SKYOJ\html($nickname[$row['uid']])?></a></td>
                        <td><a href="<?=$SkyOJ->uri('problem','view',$row['pid'])?>" title="<?=\SKYOJ\html(\SKYOJ\Problem::get_title($row['pid']))?>">
                            <span class="hidden-xs"><?=\SKYOJ\html(\SKYOJ\Problem::get_title($row['pid']))?></span>
                            <span class="visible-xs-inline"><?=$row['pid']?></span>
                        </a></td>
                        <td><span id="cid-<?=$row['cid']?>"><?=\SKYOJ\getresulttexthtml($row['result'])?></span></td>
                        <td class="hidden-xs"><?=$row['runtime']?></td>
                        <td><?=$row['score']?></td>
                        <td><?=$row['timestamp'];?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>