<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
</head>
<body>
<div id="wrap"> 
    <nav id="nav" role="navigation">
        <div class="container" id="mainnavbar">
  	        <a href="index.php"><?php echo($_E['site']['name']);?></a>
            <ul>
                <li><a href="rank.php">Stats</a></li>
	            <li><a href="http://forum.tfcis.org/forum.php?mod=group&fid=107" target="_new">Discuss</a></li>
	        </ul>
	        <ul class="pull-r">
	            <?php if( !$_G['uid']): ?>
                <li><a href="user.php?mod=login">LOGIN</a></li>
                <?php else: ?>
                <li><a href="user.php?mod=view"><?php echo(htmlspecialchars($_G['nickname']));?></a></li>
                <li><a href="user.php?mod=logout">LOGOUT</a></li>
                <?php endif;?>
            </ul>
        </div>
    </nav>
    <?php if($_E['template']['alert']):?>
    <div class="alert alert-danger fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <strong>Oh! My GOD</strong> <?php echo($_E['template']['alert']); ?>
    </div>
    <?php endif;?>
    <?php if($_E['template']['error']):?>
    <div class="alert alert-danger fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <strong>Oh! System error</strong>
        <ul>
        <?php foreach($_E['template']['error'] as $list){ ?>
            <li>(<?=$list['namespace']?>)<?=$list['msg']?></li>
        <?php }?>
        </ul>
    </div>
    <?php endif;?>