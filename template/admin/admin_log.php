<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<div class="container">

    <div>
        <div class="page-header">
                <h1>System Logs</h1>
        </div>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 180px" >TIME</th>
                <th style = "width: 180px" class = "hidden-xs">NAMESPACE</th>
                <th>DESC</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($tmpl['syslog'] as $row){ ?>
            <tr>
                <td><?=htmlentities($row['timestamp'])?></td>
                <td><?=htmlentities($row['namespace'])?></td>
                <td><?=htmlentities($row['description'])?></td>
            </tr>
        <?php }?>
        </tbody>
        
        </table>
    </div>
</div>