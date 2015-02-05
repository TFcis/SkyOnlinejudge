<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
	var SCONT;
	var old = 'modify';
	$(document).ready(function(){
        SCONT = document.getElementById('setting-main');
        SubloadTemplate(old);
        $( "[settingpage]" ).click(function(){SubloadTemplate($(this).attr('settingpage'));});
	});
	function SubloadTemplate(template){
        //alert(template);
        $("[settingpage = '"+old+"']").removeClass();
        $("[settingpage = '"+template+"']").addClass('active');
        old = template;
        $(SCONT).load("user.php?mod=view&page="+template+"&id=<?=$_E['template']['showid']?>",function(){
            $(SCONT).hide();
            $(SCONT).fadeIn();
        });
	}
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="panel">
                <li settingpage="account" role="presentation"><a>帳號</a></li>
                <li settingpage="ojacct"  role="presentation"><a>OJ設定</a></li>
                <li settingpage="modify"  role="presentation"><a >OLD</a></li>
            </ul>
        </div>
        <div class="col-md-10" id="setting-main"></div>
    </div>
</div>
