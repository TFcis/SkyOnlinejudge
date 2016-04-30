<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div id = "image-bar"></div>
<div class="container">
	<div class="page-header">
        <h1>Challenge<small></small></h1>
    </div>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>使用者</th>
						<th>題目</th>
						<th>結果</th>
						<th>時間</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<center>
        <?php Render::renderPagination(
        $_E['template']['challenge_list_pagelist'],
        $_E['SITEROOT']."challenge.php/list/%d",
        $_E['template']['challenge_list_now']) ?>
        </center>
</div>