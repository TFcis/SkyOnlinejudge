<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<h1>可用的OJ</h1>
<p>AAA</p>
<?php foreach($_E['template']['oj'] as $oj){ ?>
<p><?=$oj['name']?>:<?=$oj['description']?>:id=<?=$oj['class']?></p>
<?php } ?>