<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}

?>
<center>

<div id = 'background'>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-2"></div>
            <div class="col-lg-8 col-md-8 trans_form_mh300">
                <center>
                    <h3>公告</h3>
                </center>
                    <div id = "announce" style = "overflow-x:hidden; overflow-y:auto; overflow:hidden;">
                        <center class = "title"><?=$_E['template']['news_announce']['title']?></center>
                        <div style = "word-break: break-all;">
                            <?=$_E['template']['news_announce']['announce']?>
                        </div>
                    </div>
            </div>
            <div class="col-lg-2 col-md-2"></div>
        </div>
    </div>
</div>

</center>