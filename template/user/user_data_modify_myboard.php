<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div class="container">
    <div class="row">
        <h1>My board</h1>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 40px"></th>
                <th>NAME</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_E['template']['row'] as $row) {
    ?>
            <tr style = "height: 40px">
                <td><?=$row['id'];
    ?></td>
                <td><a href="<?=$_E['SITEROOT']?>rank.php?mod=cbedit&id=<?=$row['id'];
    ?>"><?=htmlspecialchars($row['name']);
    ?></a></td>
            </tr>
        <?php 
}?>
        </tbody>
        </table>
    </div>
</div>