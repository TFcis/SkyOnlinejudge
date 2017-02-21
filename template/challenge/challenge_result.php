<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_INPUT_HIDDEN;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_INPUT_BUTTOM;
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
		<?php if( \userControl::isAdmin($_G['uid']) ): ?>
		<script>
			$(document).ready(function()
			{
				$("#cont_comment").submit(function(e)
				{
					e.preventDefault();
					api_submit("<?=$SkyOJ->uri('chal','api','modify_comment')?>","#cont_comment","#btn-show",function(){
						setTimeout(function(){location.reload();},500);
					});
					return true;
				});
			})
			</script>
			<?php
				Render::renderForm(new FormInfo([
                    'data' => [
                        new HTML_INPUT_HIDDEN(['name' => 'cid','value'=>$data['cid']]),
                        new HTML_INPUT_TEXT(  ['name' => 'result','value'=>$data['result'],'option'=>['help_text'=>'result code']]),
                        new HTML_INPUT_TEXT(  ['name' => 'comment','value'=>$data['comment'],'option'=>['help_text'=>'comment']]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ],
                ]),"cont_comment");?>
		<?php elseif( !empty($data['comment']) ):?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">Admin's Comment</div>
					<div class="panel-body">
						<div class="container-fluid">
							<tt><?=nl2br(htmlspecialchars($data['comment']))?></tt>
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