<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
	var CONT;
	var old = '<?=$tmpl['view']['defaultpage']?>' ;
	$(document).ready(function(){
	    CONT = document.getElementById('content');
	    $( "[navpage]" ).click(function(){loadTemplate($(this).attr('navpage'));});

        $("[navpage='"+old+"']").addClass('active');
        loadTemplate(old);
	});
	
	function loadTemplate(template){
        $("[navpage='"+old+"']").removeClass();
        $("[navpage='"+template+"']").addClass('active');
        var data = {tmpl:old,call:'loadTemplate'};
        old = template;
        history.pushState(data,"Setting "+template,'<?=$SkyOJ->uri('user','view',$tmpl['showid'])?>/'+template);
        loadTemplateToBlock(template,'content','tmpl');
        return ;
	}
	function loadTemplateToBlock( template , bid  ){
	    var content = document.getElementById(bid);
	    if( content === null )return false;
	    $(content).load("<?=$SkyOJ->uri('user','view',$tmpl['showid'])?>/"+template+"?subpage=yes",function(){
            $(content).hide();
            $(content).fadeIn();
        });
	}
</script>

<div class="jumbotron" id="jumbotron" style="background-image: url(<?=$tmpl['backgroundurl']?>); background-size: cover">
    <div class="container">
        
        <div class = "col-xs-5 col-sm-4 col-md-3">
            <img src="<?=$tmpl['avaterurl']?>" id="user_view_avater">
        </div>
        
        <div style = "width: 70%; float: left" class = "col-md-7">
            <h1><?=$tmpl['nickname']?><h1>
            <?php if (!empty($tmpl['quote'])): ?>
            <blockquote style = "font-size: 14px">
                <p><?=$tmpl['quote']?></p>
                <?php if (!empty($tmpl['quote_ref'])): ?>
                <footer><?=$tmpl['quote_ref']?></footer>
                <?php endif; ?>
            </blockquote>
            <?php endif; ?>
        </div>
        
    </div>
</div>
<div class="container">
    <div style="width:100%;"></div>
    <ul class="nav nav-pills nav-pills-pink" role="tablist" style="margin-bottom:20px;">
        <li role="presentation" navpage="summary"><a>Overview</a></li>
        <li role="presentation" navpage="solve">  <a>Statstistics</a></li>
        <li role="presentation" navpage="setting"><a>Setting</a></li>
    </ul>

    <div class="tab-content" id="content" style="min-height:100px; padding: 0 10px"></div>
    <div style="width:100%;height:100px"></div>
</div>