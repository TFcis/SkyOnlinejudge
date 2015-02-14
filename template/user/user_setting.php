<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<script>
	var SCONT;
	var sold = 'account';
	function SubloadTemplate(template){
        $("[settingpage = '"+sold+"']").removeClass();
        $("[settingpage = '"+template+"']").addClass('active');
        sold = template;
        $(SCONT).load("user.php?mod=view&page="+template+"&id=<?=$tmpl['showid']?>",function(){
            $(SCONT).hide();
            $(SCONT).fadeIn();
        });
	}
	$(document).ready(function(){
        SCONT = document.getElementById('setting-main');
        $( "[settingpage]" ).click(function(){SubloadTemplate($(this).attr('settingpage'));});
        SubloadTemplate(sold);
	});
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="panel">
                <li settingpage="profile" role="presentation"><a>profile</a></li>
                <li settingpage="account" role="presentation"><a>帳號</a></li>
                <li settingpage="ojacct"  role="presentation"><a>OJ設定</a></li>
                <li settingpage="myboard"  role="presentation"><a>我的記分板</a></li>
            </ul>
        </div>
        <div class="col-md-10" id="setting-main"></div>
    </div>
</div>
