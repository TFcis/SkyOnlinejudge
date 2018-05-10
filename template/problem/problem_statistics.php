<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script src="<?=$_E['SITEROOT']?>js/third/Chart.js/Chart.bundle.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h3><?=$tmpl['problem']->pid?>. <?=\SKYOJ\html($tmpl['problem']->title)?>
            <a class="icon-bttn" href="<?=$SkyOJ->uri('problem','view',$tmpl['problem']->pid)?>/">
                <span class="pointer glyphicon glyphicon-arrow-left" title="回到題目"></span>
            </a>
            </h3>
        </div>
        <div class="col-md-3 text-right">
            <p><?=array_search(0/*$tmpl['problem']->GetJudgeType()*/,SKYOJ\ProblemJudgeTypeEnum::getConstants())?> Judge</p>
            <p>Code: <?=array_search(0/*$tmpl['problem']->GetCodeviewAccess()*/,SKYOJ\ProblemCodeviewAccessEnum::getConstants())?></p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Top 20 Coder<small></small></h1>
                <table class = "table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>User</th>
                            <th>Runtime</th>
                            <th>Memory</th>
                            <th>Submit ID</th>
                            <th>Timestamp</th>
                        <tr>
                    </thead>
                    <tbody>
                    <?php $i=0;foreach($tmpl['rank_chal'] as $row):$i++ ?>
                    <tr>
                        <td><?=$i?></td>
                        <?php $nickname = \SKYOJ\nickname($row->uid); ?>
                        <td><?=\SKYOJ\html($nickname[$row->uid])?></td>
                        <td><?=$row->runtime?></td>
                        <td><?=$row->memory?></td>
                        <td><a href="<?=$SkyOJ->uri('chal','result',$row->cid)?>">#<?=$row->cid?></a></td>
                        <td><?=$row->timestamp?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="background:#FFF">
            <canvas id="problempie" width="100" height="100"></canvas>
            <script>
                var ctx = $("#problempie");
                var data = <?=json_encode($tmpl['chart']);?>;
                var myChart = new Chart(ctx,{
                    type:'pie',
                    data:data,
                });
            </script>
        </div>
    </div>
</div>