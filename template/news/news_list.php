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
                <div class="table‐responsive">
                    <table class = "table table‐striped">
                        <thead>
                            <tr>
                                <th style = 'width: 200px'>Time</th>
                                <th style = 'width: 500px'>Title</th>
                                <?php //if ($_G['uid']): ?>
                                <th style = 'width: 100px'>
                                    Tools 
                                    <a class = "icon-bttn" title = "Create New" href="#">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </a>
                                </th>
                                <?php //endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($_E['template']['news_info'] as $row) { ?>
                            <tr>
                                <td style = "word-break: break-all;"><?=$row['timestamp']?></td>
                                <td style = "word-break: break-all;"><a href="<?=$_E['SITEROOT']?>news.php/announce/<?=$row['id']?>"><?=$row['title']?></td>
                                <td style = "word-break: break-all;"></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-2 col-md-2"></div>
        </div>
    </div>
</div>

</center>