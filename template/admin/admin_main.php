<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
	var CONT;
	var old = 'dashboard';
	$(document).ready(function(){
	    CONT = document.getElementById('content');
	    $( "[navpage]" ).click(function(){loadTemplate($(this).attr('navpage'));});
        $("[navpage='"+old+"']").addClass('active');
        loadTemplate(old);
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
	    $(content).load("<?=$_E['SITEROOT']?>admin.php/"+template,{_t:'<?=$tmpl['ADMIN_CSRF']?>'},function(){
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
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation" navpage='dashboard'><a href="#">摘要</a></li>
            <li role="presentation" navpage='log'><a href="#">系統紀錄</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="plugindb">插件<span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="plugindb">
                    <?php foreach ($_E['template']['pluginfolders'] as $folder) {
    ?>
                    <li navpage="plugins/list/?folder=<?=urlencode($folder)?>"><a href="#"><?=htmlentities($folder)?></a></li>
                    <?php 
}?>
                </ul>
            </li>
        </ul>
      </div>
      <div class="col-sm-10 col-md-10" id="main-page"></div>
    </div>
</div>
