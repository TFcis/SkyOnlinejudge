<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
	var CONT;
	var old;
	$(document).ready(function(){
        CONT = document.getElementById('content');
        var curTemplate = location.hash.slice(1);
        if(curTemplate=="")	{ curTemplate = "setting"; }
        $("[navpage='"+curTemplate+"']").addClass('active');
        old = curTemplate;
        loadTemplate(curTemplate);
        $( "[navpage]" ).click(function(){loadTemplate($(this).attr('navpage'));});
	});
	function loadTemplate(template){
        location.hash = template;
        $("[navpage='"+old+"']").removeClass();
        $("[navpage='"+template+"']").addClass('active');
        old = template;
        $(CONT).load("user.php?mod=view&page="+template+"&id=<?=$tmpl['showid']?>",function(){
            $(CONT).hide();
            $(CONT).fadeIn();
        });
        
	}
</script>

<div class="jumbotron" id="jumbotron" style="background-image: url(http://i.imgur.com/n2EOWhO.jpg); background-size: cover">
    <div class="container">
        
        <div class = "col-xs-5 col-sm-4 col-md-3">
            <img src="<?=$tmpl['avaterurl']?>" id="user_view_avater">
        </div>
        
        <div style = "width: 70%; float: left" class = "col-md-7">
            <h1><?=$tmpl['nickname']?><h1>
            <blockquote style = "font-size: 14px">
                <p><?=$tmpl['quote']?></p>
                <footer>By Pokemon</footer>
            </blockquote>
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