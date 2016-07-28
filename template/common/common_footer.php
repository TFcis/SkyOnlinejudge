<?php
    if (!defined('IN_TEMPLATE')) {
        exit('Access denied');
    }
?>
<div id="push"></div>
</div> <!-- end wrap -->
<?php $u = getrusage();?>
<div id="footer"><small>Developed By LFsWang/Sylveon @ <a href='https://github.com/TFcis/SkyOnlinejudge' target="_blank">Github</a>(<?=$u['ru_utime.tv_sec']?>ms)</small></div>
</body>