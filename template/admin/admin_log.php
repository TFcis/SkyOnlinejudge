<?php
if(!defined('IN_TEMPLATE'))
{
  exit('Access denied');
}
?>
<div class="container">

    <div>
        <div class="page-header">
            <h1>System Logs<small>Debug只會紀錄在MsgShower</small></h1>
        </div>
        <table class = "table">
        <thead>
            <tr>
                <th style = "width: 180px" >TIME</th>
                <th style = "width: 120px" class = "hidden-xs">LEVEL</th>
                <th>DESC</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($tmpl['syslog'] as $row){ ?>
            <tr>
                <td><?=htmlentities($row['timestamp'])?></td>
                <td><?=htmlentities(LevelName($row['level']))?></td>
                <td><?=htmlentities($row['message'])?></td>
            </tr>
        <?php }?>
        </tbody>
        
        </table>
    </div>
</div>