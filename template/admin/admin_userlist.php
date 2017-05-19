<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">

    <div>
        <div class="page-header">
            <h1>Userlist<small>Manage User</small></h1>
        </div>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 90px" >Uid</th>
                <th style = "width: 180px">Nickname</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tmpl['userlist'] as $row) { ?>
            <tr>
                <td><?=htmlentities($row['uid'])?></td>
                <td><a href="<?=$SkyOJ->uri('user','view',$row['uid'])?>"><?=htmlentities($row['nickname'])?></a></td>
            </tr>
        <?php } ?>
        </tbody>
        
        </table>
        <center>
        <?php Render::renderPagination(
        $_E['template']['userlist_page_list'],
        "userlist?page=%d&pagelist",
        $_E['template']['userlist_now'],
        true) ?>
        </center>
    </div>
</div>