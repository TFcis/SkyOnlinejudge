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
<?php
    var_dump($_G);
?>
<h1>MUSIC LOGIN ONLY~</h1>
<?php if($_G['uid']): ?>

<audio controls>
  <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.mp3">
  <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.ogg">
  HTML5 ONLY! 請升級您的瀏覽器
</audio>


<?php endif; ?>