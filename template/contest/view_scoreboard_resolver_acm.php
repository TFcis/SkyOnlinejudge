<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

</head><!--close head--><!-- copy from https://www.acm-icpc.eng.chula.ac.th/2016 -->
<link href="<?=$_E['SITEROOT']?>css/third/bangkok-resolver/bootstrap.min.css" rel="stylesheet">
<link href="<?=$_E['SITEROOT']?>css/third/bangkok-resolver/resolver_acm.css" rel="stylesheet">


<div id="scoreboard" class="scoreboard">
    <div id="table-head" class="scoreboard-head scoreboard-row">
        <div class="cell scoreboard-rank">#</div>
        <div class="cell scoreboard-name">TEAM</div>
        <div class="cell scoreboard-score" colspan="2">SCORE</div>
        <div class="scoreboard-problem-list"></div>
    </div>
    <div id="scoreboard-body">
    </div>
    <div id="scoreboard-foot" class="scoreboard-foot scoreboard-row">
        <div class="cell text-center" style="padding: 5px 30px;">
            <div class="pull-left">
                <?=\SKYOJ\html($tmpl['contest']->title)?>
            </div>
            <div class="pull-right">
                <span class="label label-example"><b>Attempts</b> (Points)</span>
                <span class="label label-first">First Solver</span>
                <span class="label label-solved">Solved</span>
                <span class="label label-tried">Attempted</span>
                <span class="label label-pending">Pending</span>
            </div>
        </div>
    </div>
</div>

<div id="notrunning" class="col-md-8 col-md-offset-2" style="display: none;">
    <div class="text-center">
        <h1 style="color: white;">CONTEST IS NOT RUNNING</h1>
    </div>
</div>
<!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
result_version = 0;
scoreboardUpdateTime = 10000;
g_before_url = <?=json_encode($SkyOJ->uri('contest','api','bangkok_results_before')."?cont_id=".$tmpl['contest']->cont_id().'&version='.time())?>;
g_final_url =  <?=json_encode($SkyOJ->uri('contest','api','bangkok_results_final')."?cont_id=".$tmpl['contest']->cont_id().'&version='.time())?>;
</script>
<script src="<?=$_E['SITEROOT']?>js/third/bangkok-resolver/handlebars.min.js"></script>
<script src="<?=$_E['SITEROOT']?>js/third/bangkok-resolver/live_acm.js"></script>
