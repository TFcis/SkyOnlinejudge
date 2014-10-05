<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
</head>
<body>
<div id="wrap"> 
    <nav class="nav" role="navigation">
        <div class="container">
  	        <a href="index.php"><?php echo($_E['site']['name']);?></a>
            <ul>
                <li><a href="rank.php">排行</a></li>
	            <li><a href="http://forum.tfcis.org/forum.php?mod=group&fid=107" target="_new">Discuss</a></li>
	        </ul>
	        <ul class="pull-r">
	            <?php if(!$_G['uid']): ?>
                <li><a href="user.php?mod=login">登入</a></li>
                <?php else: ?>
                <li><a href="user.php?mod=view"><?php echo(htmlspecialchars($_G['nickname']));?></a></li>
                <li><a href="user.php?mod=logout">登出</a></li>
                <?php endif;?>
            </ul>
        </div>
    </nav>