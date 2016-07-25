<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <h1>My Codes</h1>
        <table class = "table">
        <thead>
            <tr>
                <th class = 'col-lg-1' style = "width: 40px"></th>
                <th>TITLE</th>
                <th class = 'col-lg-1'>Tools</th>
                <th class = 'col-lg-3' >TIME</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['row'] as $row) {
    ?>
            <tr style = "height: 40px">
                <td><?=$row['id'];
    ?></td>
                <td><a href="<?=$SkyOJ->uri('code','view',$row['hash'])?>"><?=htmlspecialchars($row['hash']);
    ?></a></td>
                <td>
                    <a class="icon-bttn">
                        <span class="glyphicon glyphicon-pencil" title="編輯"></span>
                    </a>
                    <a class="icon-bttn">
                        <span class="glyphicon glyphicon-trash" title="移除"></span>
                    </a>
                </td>
                <td><?=$row['timestamp'];
    ?></td>
            </tr>
        <?php 
}?>
        </tbody>
        </table>
    </div>
</div>