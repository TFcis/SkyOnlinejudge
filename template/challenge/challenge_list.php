<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<div id = "image-bar"></div>
<div class="container">
	<div class="page-header">
        <h1>Challenge<small></small></h1>
        <buttom class="btn btn-success" adv-act="search" data-toggle="modal" data-target="#search">search</buttom>
    </div>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>User</th>
						<th>Problem</th>
						<th>Rate</th>
						<th>Timestamp</th>
					</tr>
				</thead>
				<tbody>
               <?php foreach ($_E['template']['challenge_info'] as $row) {
    ?>
					<tr>
                       <td><a href="<?=$_E['SITEROOT']?>challenge.php/result/<?=$row['id']?>"><?=$row['id'];
    ?></a></td>
						<?php 
                        $row['user'] = (string) $row['user'];
    $nickname = \SKYOJ\nickname($row['user']);
    ?>
                       <td><a href="<?=$_E['SITEROOT'].'user.php/view/'.$row['user']?>"><?=$nickname[$row['user']]?></a></td>
                       <td><a href="<?=$_E['SITEROOT']?>problem.php/problem/<?=$row['problem'];
    ?>"><?=$row['problem'];
    ?></a></td>
                       <td><?=\SKYOJ\getresulttext($row['result']);
    ?></td>
                       <td><?=$row['time'];
    ?></td>
					</tr>
               <?php 
}?>
				</tbody>
			</table>
			<center>
        <?php Render::renderPagination(
        $_E['template']['challenge_list_pagelist'],
        $_E['SITEROOT'].'challenge.php/list/%d?pid='.$_E['template']['challenge_pid'].'&uid='.$_E['template']['challenge_uid'],
        $_E['template']['challenge_list_now']) ?>
        </center>
</div>

<!--search modal-->
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">search</h4>
            </div>
            <form id="search_form" method="GET" action="<?=$_E['SITEROOT']?>challenge.php">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="pid">problem</label>
                            <input type="text" class="form-control" id="pid" name="pid">
                        </div>
                        <div class="form-group">
                            <label for="uid">user</label>
                            <input type="text" class="form-control" id="uid" name="uid">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default" id="searchsubmit">search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--/search modal-->
