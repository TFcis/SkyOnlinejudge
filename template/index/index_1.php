<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>



<h1>HIHI</h1>
Math Test<br>
$a,\ b(a,\ b\ <\ 2^{63})$
<br>

<h1>MUSIC LOGIN ONLY~</h1>
<?php if($_G['uid']): ?>

<audio controls>
  <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.mp3">
  <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.ogg">
  HTML5 ONLY! 請升級您的瀏覽器
</audio>
<br>
<iframe width="560" height="315" src="//www.youtube.com/embed/videoseries?list=PLvLX2y1VZ-tEmqtENBW39gdozqFCN_WZc" frameborder="0" allowfullscreen></iframe>
<?php endif; ?>