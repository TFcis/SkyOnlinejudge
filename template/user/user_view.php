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
		var curTemplate = 'summary';
		old = curTemplate;
        loadTemplate(curTemplate);
        
        $('#nav-summary').click(function(){loadTemplate('summary'); });
        $('#nav-solve').click(function(){  loadTemplate('solve');   });
        $('#nav-modify').click(function(){ loadTemplate('modify');  });
	});
	function loadTemplate(template){
		location.hash = template;
		$('#li-'+old).removeClass();
		$('#li-'+template).addClass('active');
		old = template;
    	$(CONT).load("user.php?mod=view&page="+template+"&id=<?=$_E['template']['showid']?>");
	}
</script>

<div class="jumbotron" id="jumbotron" style="background-image: url(http://aeea.nmns.edu.tw/geo_home/GEO95/image2/devi.jpg);">
    <div class="container">
        <div class="col-xs-5 col-sm-4 col-md-3">
            <img src="<?=$_E['template']['avaterurl']?>" id="user_view_avater">
        </div>
        <div class="col-xs-12 col-sm-8 col-md-7">
            <h1>[L]<?=$_E['template']['nickname']?><h1>
            <blockquote>
                <p><?=$_E['template']['quote']?></p>
                <footer>By Pokemon</footer>
            </blockquote>
        </div>
    </div>
</div>
<div style="width:100%;margin-bottom:-30px;"></div>

<ul class="nav nav-tabs navbar-inverse" role="tablist" id="nav-userview">
    <li role="presentation" id="li-summary" class="active">
        <a id="nav-summary">總覽</a>
    </li>
    <li role="presentation" id="li-solve">
        <a id="nav-solve">解題紀錄</a>
    </li>
    <li role="presentation" id="li-modify">
        <a id="nav-modify">修改資料</a>
    </li>
</ul>

<div class="tab-content" id="content">
</div>
