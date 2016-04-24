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
            <time>2015-2-17</time>更新
            <ol>
                <li>更新了標題列</li>
                <li>更新了部分網址呈現方式</li>
                <li>修正Cookie的路徑問題</li>
                <li>增加Codepad功能</li>
                <li>User Setting頁面新增了My board,My Code,Profile</li>
                <li>計分板移入選單中</li>
                <li>可以自定義引言了</code></li>
                <li>新增了參數 <code>$_E['SITEDIR'] = '/';</code> 設定OJ在Web端的目錄，以符合目前網址格式設計，安裝時請依實際狀況在<code>LocalSetting.php</code>修改</li>
                <li>語言需要有人來統整一下，整理現在有點混亂的用語</code></li>
            </ol>
        </div>
        <br>
        <div>
            <time>2015-2-3</time>更新
            <ol>
                <li>在大家的努力下增加了很多OJ~ XD</li>
                <li>追加了<code>syslog</code>資料表</li>
                <li>為開發者放了一個admin頁面可以看log，需登入且具有權限才可見</li>
                <li>使用<code>DB::syslog(CONTENT [,NAMESPACE] )</code>可以輸出log到DB，此外SQL錯誤會自動記錄</li>
                <li>增加<code>DB::real_escape_string(STR)</code>統一的過濾字串</li>
                <li>增加<code>DB::query(Q,errorno)</code>增加一個參數避免自動記錄SQL錯誤，預設為<code>false</code></li>
                <li>class_cf會導致cbfetch發生錯誤，於下版本改進</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2015-1-13</time>更新
            <ol>
                <li>優化Code of User View page</li>
                <li>支援 <a href='https://en.gravatar.com/' target="_blank">Gravatar</a></li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-16</time>更新
            <ol>
                <li>CBBOARD 可以透過 <code>challink(uid,pid)</code> 取得解題改況連結，點擊圓點即可使用</li>
                <li>修復 cbfetch 一個錯誤建構空白頁面的方法</li>
                <li>模板文件<code>$_E['template']</code> 可以用<code>$tmpl</code>取代</li>
                <li>更好看的登入介面，圖片版權：創用CC <a href='https://commons.wikimedia.org/wiki/File:Sunset_(2).jpg' target="_blank">Sunset(公眾領域使用)</a>,
                <a href="http://www.public-domain-image.com/nature-landscape/hill/slides/strandhill-ireland-ocean-beaches-couds-sky.html" target="_blank">Strandhill ireland ocean beaches couds sky(公眾領域使用)</a>。</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-12</time>更新
            <ol>
                <li>TOJ驗證功能Open!</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-10</time>更新
            <ol>
                <li>可以修改密碼</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-9</time>更新
            <ol>
                <li>修復ZJ需要token的問題</li>
                <li>修復UVA一個愚蠢的變數名稱錯誤</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-4</time>更新
            <ol>
                <li>更新過時的MYSQL函數</li>
            </ol>
        </div>
        <br>
        <div>
            <time>2014-12-3</time>更新
            <ol>
                <li>改善手機瀏覽介面</li>
                <li>更新UVA插件</li>
                <li>獨立設定檔，此版本後須自行建立 <code> LocalSetting.php </code> 以個性化網站</li>
            </ol>
        </div>
        <br>
        <!--Math Test<br>
        $a,\ b(a,\ b\ <\ 2^{63})$
        <br>-->
    </div>
    <div class="row">
        <h1>展望</h1>
        <dl class="dl-horizontal">
          <dt>廣用型記分板</dt>
          <dd>
                <ul>
                    <li><p>加入方法</p>
                    <ol>
                        <li>自由加入退出</li>
                        <li>申請制</li>
                        <li>僅管理員可修改</li>
                    </ol></li>
                    <li><p>及格分隔線</p></li>
                    <li><p>排名，可自訂計分方法</p></li>
                </ul>
          </dd>
        </dl>
    </div>
    <div class="row">
        <h1>MUSIC LOGIN ONLY~</h1>
        <?php if($_G['uid']): ?>
        <div class="col-md-5">
            <audio controls>
                <source src="http://pc2.tfcis.org/lfs/20140830/egg.mp3">
                <source src="http://pc2.tfcis.org/lfs/20140830/egg.ogg">
                HTML5 ONLY! 請升級您的瀏覽器
            </audio>
            <br>
            <div class="embed-responsive embed-responsive-4by3">
                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/videoseries?list=PLvLX2y1VZ-tEmqtENBW39gdozqFCN_WZc" allowfullscreen></iframe>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="row">
        <h2><a href="http://www.tfcis.org/~forummaker/sojwiki/">SOJWiki</a></h2>
    </div>
</div>
