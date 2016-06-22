<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
$data = $_E['template']['challenge_result_info'][0];
$result = $_E['template']['challenge_result_info']['result'];
?>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td>上傳編號</td>
						<td><?=$data['id']?></td>
					</tr>
					<tr>
						<td>時間</td>
						<td><?=$data['time']?></td>
					</tr>
					<tr>
						<td>題目</td>
						<td><?=$data['problem']?></td>
					</tr>
					<tr>
						<td>使用者</td>
						<?php
                        $user = (string) $data['user'];
                        $nickname = nickname($user);
                        ?>
						<td><?=$nickname[$user]?></td>
					</tr>
					<tr>
						<td>總得分</td>
						<td><?=$data['score']?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-8">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>state</th>
						<th>runtime</th>
                        <th>peakmem</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach($result as $i) {?>
					<tr>
                        <td><?=$i->test_idx?></td>
						<td><?=getresulttext($i->state)?></td>
						<td><?=$i->runtime?></td>
                        <td><?=$i->peakmem?></td>
					</tr>
                    <?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<p>
				<em>CODE</em>
			</p>
		</div>
	</div>
</div>