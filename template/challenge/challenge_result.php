<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
$data = $tmpl['challenge_result_info'];
$result = json_decode($data['package'],true) ?? [];
//$result = $_E['template']['challenge_result_info']['result'];
?>
<script>
$(document).ready(function()
{
    var editor = ace.edit("rcode");
	var sec = 1;
    editor.setReadOnly(true);
	<?php if( $data['result'] < \SKYOJ\RESULTCODE::AC):?>
	updateJudgeVerdict("<?=$SkyOJ->uri('chal','api','waitjudge')?>",<?=$data['cid']?>,function(cid,res){
		location.reload();
	});
	<?php endif ;?>
})
</script>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td>上傳編號</td>
						<td><?=$data['cid']?></td>
					</tr>
					<tr>
						<td>上傳時間</td>
						<td><?=$data['timestamp']?></td>
					</tr>
					<tr>
						<td>題目</td>
						<td>
							<a href="<?=$SkyOJ->uri('problem','view',$data['pid'])?>">
								<?=\SKYOJ\html(\SKYOJ\Problem::get_title($data['pid']))?>
							</a>
						</td>
					</tr>
					<tr>
						<td>使用者</td>
						<?php
                        $nickname = \SKYOJ\nickname($data['uid']);
                        ?>
						<td>
							<a href="<?=$SkyOJ->uri('user','view',$data['uid'])?>">
								<?=\SKYOJ\html($nickname[$data['uid']])?>
							</a>
						</td>
					</tr>
					<tr>
						<td>總得分</td>
						<td><?=$data['score']?>, <?=\SKYOJ\getresulttexthtml($data['result'])?>
							<?php if( $data['result'] >= \SKYOJ\RESULTCODE::AC && $data['result'] <= \SKYOJ\RESULTCODE::CE): ?>
								 in <?=$data['runtime']?> ms
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-8">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>State</th>
						<th>Runtime</th>
                        <th>Memory</th>
					</tr>
				</thead>
				<tbody>
                <?php $t = '' ; foreach ($result as $i): $t = $i['msg']??''?>
					<tr>
                        <td><?=$i['taskid']?></td>
						<td><?=\SKYOJ\getresulttexthtml($i['state'])?></td>
						<td><?=$i['runtime']?></td>
                        <td><?=$i['mem']?></td>
					</tr>
                <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php if( $data['uid']==$_G['uid'] || \userControl::isAdmin($_G['uid']) ):?>
		<?php if(\userControl::isAdmin($_G['uid'])):?>
		<div class="row">
			<a href="<?=$SkyOJ->uri('problem','api','judge')?>?cid=<?=$data['cid']?>">Rejudge</a>
		</div>
		<?php endif;?>
		<?php if( !empty($t) ):?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Judge Information</div>
					<div class="panel-body">
						<div class="container-fluid">
							<tt><?=nl2br(htmlspecialchars($t))?></tt>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
		<div class="row">
			<div class="col-md-12">
				<?php 
					$tmpl['defaultcode'] = $data['code'];
					Render::renderCode($data['code'],'c_cpp','rcode'); 
				?>
			</div>
		</div>
	<?php endif;?>
</div>