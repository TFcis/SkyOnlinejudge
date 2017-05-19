<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>

<script>
$(document).ready(function()
{
    $("#search").submit(function(e)
    {
        e.preventDefault();
        search_nick = $("#nickname").val();
        loadTemplate('userlist?page=1&nickname='+search_nick);
    });
});
</script>

<div class="container">

    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <h1>Userlist<small>Manage User</small></h1>
                <form class="form-inline" action="admin.php" id="search">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nickname" placeholder="nickname">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </form>
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
            "userlist?page=%d&nickname=".$_E['template']['userlist_search'],
            $_E['template']['userlist_now'],
            true) ?>
            </center>
        </div>
    </div>
</div>