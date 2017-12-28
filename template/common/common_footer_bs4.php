<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
$u = getrusage();
$t = date("Y-m-d H:i:s");
?>
<div id="push"></div>
</div> <!-- end wrap -->
<div id="footer"><small><span id="timer"><?=$t?></span> Developed By LFsWang/Sylveon @ <a href='https://github.com/TFcis/SkyOnlinejudge' target="_blank">Github</a>(<?=$u['ru_utime.tv_sec']?>ms)</small></div>
</body>