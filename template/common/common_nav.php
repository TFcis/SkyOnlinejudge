<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_PASSWORD;
?>
</head>
<?php
    if (isset($tmpl['_body_class'])) {
        $classtmp = implode(' ', $_E['template']['_body_class']);
        $classtmp = "class='$classtmp'";
    } else {
        $classtmp = '';
    }
?>
<?php if(userControl::isAdmin($_G['uid'])):?>
<script>
function updateAdminToken(succ_callback,fail_callback){
    var token = getParameterByName('token');
    $.post('<?=$SkyOJ->uri('admin','api','CheckAdminToken')?>',{token:token},function(res){
        if( res.status == 'error' ){  
            $('#adminPassInput').modal('show');
            if (typeof fail_callback != 'undefined')
                fail_callback(token);
        }else{
            if (typeof succ_callback != 'undefined')
                succ_callback(token);
        }
    },"json").error(function(e){
        console.log(e);
    });
}
$(document).ready(function()
{
    $("#admin-check").attr('onsubmit','event.preventDefault(); return false;');
    
    last_call = null;
    $("#admin-check-submit").click(function(e){
        api_submit('<?=$SkyOJ->uri('admin','api','GetAdminToken')?>','#admin-check','#admin-check-info',function(res){
            updateQueryStringParameter('token',res.data);
            if( last_call !== null )last_call(res.data);
            last_call = null;
        });
    });

    $("#admin-tab").click(function(e)
    {
        last_call = function(token){
            location.assign('<?=$SkyOJ->uri('admin')?>'+'?token='+token, '_blank');
        };
        updateAdminToken(last_call);
    });
})
</script>
<?php endif;?>
<body <?=$classtmp?>>
<div id="wrap"> 
    <nav class="navbar navbar-default navbar-static-top navbar-inverse" style="background-color:#101010">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$_E['SITEROOT']?>index.php"><?php echo $_E['site']['name']; ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?=$SkyOJ->uri('problem','list')?>">Problems</a></li>
                    <li><a href="<?=$SkyOJ->uri('chal')?>">Challenges</a></li>
                    <li><a href="<?=$SkyOJ->uri('contest','list')?>">Contest</a></li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tools<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">Coder</li>
                            <li><a href="<?=$SkyOJ->uri('rank','list')?>">Scoreboard</a></li>
                            <li><a href="<?=$SkyOJ->uri('code')?>">Code</a></li>
                            <li class="divider"></li>
                            <li><a href="<?=$SkyOJ->uri('index','old')?>">Dev Message</a></li>
                            <li><a href="http://forum.tfcis.org/forum.php?mod=group&fid=107" target="_blank">Discuss</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (!$_G['uid']): ?>
                    <li><a href="<?=$SkyOJ->uri('user','login')?>">LOGIN</a></li>
                    <?php else: ?>
                        <?php if (userControl::isAdmin($_G['uid'])):?>
                        <li><a href="#" id="admin-tab">Admin</a></li>
                        <?php endif; ?>
                    <li><a href="<?=$SkyOJ->uri('user','view',$_G['uid'])?>"><?php echo htmlspecialchars($_G['nickname']); ?></a></li>
                    <li><a href="<?=$SkyOJ->uri('user','logout')?>">LOGOUT</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php if ($_E['template']['error']):?>
    <div class="alert alert-danger fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <strong>Oh! System error</strong>
        <ul>
        <?php foreach ($_E['template']['error'] as $list) {
    ?>
            <li>(<?=$list['namespace']?>)<?=$list['msg']?></li>
        <?php 
}?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (userControl::isAdmin($_G['uid'])):/*Admin Pass input*/?>
    <div class="modal fade" id="adminPassInput" tabindex="-1" role="dialog" aria-labelledby="adminPassInputlb">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="adminPassInputlb" style="color:black">請輸入密碼來繼續操作</h4>
                </div>
                <div class="modal-body">
                    <?php Render::renderForm(new FormInfo([
                        'data' => [
                            new HTML_INPUT_PASSWORD(['name' => 'password','required'=>'required','option' => ['help_text' => '密碼']]),
                        ],
                    ]),"admin-check");?>
                </div>
                <div class="modal-footer">
                    <small><span id='admin-check-info'></span></small>
                    <button type="button" class="btn btn-primary" id="admin-check-submit">送出</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>