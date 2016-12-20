<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
	var old = 'dashboard';
	$(document).ready(function(){
	    $( "[navpage]" ).click(function(){loadTemplate($(this).attr('navpage'));});
        $("[navpage='"+old+"']").addClass('active');
        //loadTemplate(old);
	});

	function loadTemplate(template){
        $("[navpage='"+old+"']").removeClass();
        $("[navpage='"+template+"']").addClass('active');
        old = template;
        loadTemplateToBlock(template,'main-page');
        return ;
	}
	function loadTemplateToBlock( template , bid  ){
	    var content = document.getElementById(bid);
	    if( content === null )return false;

        adder = '?';
        if( adder.indexOf('?') != -1 )
        {
            adder = '&';
        }
	    $(content).load("<?=$SkyOJ->uri('contest','view',$tmpl['contest']->cont_id(),'subpage')?>/"+template,function(){
            MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
            $(content).hide();
            $(content).fadeIn();
            $('#'+bid+' a[tmpl]').click(function(event){
                event.preventDefault();
                tmpl = $(this).attr('tmpl');
                console.log(tmpl);
                console.log(bid);
                loadTemplateToBlock(tmpl,bid);
            });
        });
	}
    //$('.dropdown-toggle').dropdown();
</script>
<div class="container">
    <div class="row">
        <div class="col-sm-2 col-md-2" style="min-height:500px">
            <div>
                <h3><?=\SKYOJ\html($tmpl['contest']->title)?></h3>
                <p>剩餘時間 : <span data-toggle="sky-countdown" data-value="<?=$tmpl['contest']->endtime?>" onclockdownzero=""></span></p>
            </div>
            <hr>
            <ul class="nav nav-pills nav-stacked">
                <?php foreach($tmpl['contest']->get_all_problems_info() as $prob):?>
                    <li role="presentation" navpage='prob_<?=\SKYOJ\html($prob->ptag)?>'><a href="#"><?=\SKYOJ\html($prob->ptag.', '.\SKYOJ\Problem::get_title($prob->pid))?></a></li>
                <?php endforeach;?>
                <li role="presentation" navpage='submit'><a href="#">上傳</a></li>
                <li role="presentation" navpage='log'><a href="#">上傳紀錄</a></li>
                <li role="presentation" navpage='scoreboard'><a href="#">記分板</a></li>
            </ul>
        </div>
        <div class="col-sm-10 col-md-10" id="main-page"></div>
    </div>
</div>
