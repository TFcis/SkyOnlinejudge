<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<div class = "container" >

    <div class="row">
        <h1>更新日誌</h1>
        <div>
            <time>2014-12-3</time>更新
            <ol>
                <li>改善手機瀏覽介面</li>
                <li>更新UVA插件</li>
                <li>獨立設定檔，此版本後須自行建立 <code> LocalSetting.php </code> 以個性化網站</li>
            </ol>
        </div>
        
        <div>
            <time>2014-12-4</time>更新
            <ol>
                <li>更新過時的MYSQL函數</li>
            </ol>
        </div>
        
        
        <br>
        Math Test<br>
        $a,\ b(a,\ b\ <\ 2^{63})$
        <br>
    </div>
    <div class="row">
        <h1>MUSIC LOGIN ONLY~</h1>
        <?php if($_G['uid']): ?>
        <div class="col-md-5">
            <audio controls>
                <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.mp3">
                <source src="http://pc2.tfcis.org:81/lfs/20140830/egg.ogg">
                HTML5 ONLY! 請升級您的瀏覽器
            </audio>
            <br>
            <div class="embed-responsive embed-responsive-4by3">
                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/videoseries?list=PLvLX2y1VZ-tEmqtENBW39gdozqFCN_WZc" allowfullscreen></iframe>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>