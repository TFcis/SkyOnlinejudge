<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
use \SKYOJ\FormInfo;
use \SKYOJ\HTML_ROW;
use \SKYOJ\HTML_HR;
use \SKYOJ\HTML_INPUT_TEXT;
use \SKYOJ\HTML_INPUT_DIV;
use \SKYOJ\HTML_INPUT_SELECT;
use \SKYOJ\HTML_INPUT_BUTTOM;
use \SKYOJ\HTML_INPUT_HIDDEN;
?>

<script src="<?=$_E['SITEROOT']?>js/third/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector:'#announcement',
        plugins :[
            "advlist autolink lists link charmap preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime nonbreaking table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ]
    });
</script>
<script>
$(document).ready(function()
{
    pageid = <?=json_encode($tmpl['sb']->sb_id)?>;
    /*$( "[adv-act]" ).click(function(){reqmode($(this).attr('adv-act'));});
    reqworking = false;
    function reqmode(mode)
    {
        if(reqworking)
        {
            return ;
        }
        reqworking = true;
        $("#adv-act-info").html('Modifying...');
        $.post("<?=$_E['SITEROOT']?>rank.php",{
            mod : 'edit',
            page: 'cb'+mode,
            id  : pageid,
        },function(res){
            if( res.status == 'SUCC' )
            {
                $("#adv-act-info").css('color','Lime');
                $("#adv-act-info").html('YES!');
                setTimeout(function(){location.reload();}, 500);
            }
            else
            {
                $("#adv-act-info").css('color','Red');
                $("#adv-act-info").html(res.data);
            }
        },"json");
        reqworking = false;
    }*/
    $("#board").submit(function(e)
    {
        e.preventDefault();
        $("#announce").val(tinymce.get('announcement').getContent());
        api_submit("<?=$SkyOJ->uri('rank','api','modify',$tmpl['sb']->sb_id)?>",'#board','#btn-show',function(res){
            setTimeout(function(){location.reload();}, 500);
        });
        return true;
    });
})
</script>
<div class="container">
    <div class="row">
        <div class="page-header">
          <h1>編輯記分板 <small><?=\SKYOJ\html($tmpl['sb']->title)?>
            <a class="icon-bttn" href="<?=$SkyOJ->uri('rank','view',$tmpl['sb']->sb_id)?>">
                <span class="pointer glyphicon glyphicon-arrow-left" title="回到記分板"></span>
            </a>
          </small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <?php
                $p = $tmpl['sb']->GetProblems();
                foreach( $p as &$row )
                    $row = $row['problem'];
                Render::renderForm(new FormInfo([
                    'data'=>[
                        new HTML_INPUT_HIDDEN(['id'=>'announce','name'=>'announce','value'=>'']),
                        new HTML_INPUT_TEXT(['name'=>'title','value'=>$tmpl['sb']->title,'option' => ['help_text' => '記分板名稱']]),
                        new HTML_INPUT_TEXT(['name'=>'start','value'=>$tmpl['sb']->start,'option' => ['help_text' => '開始時間']]),
                        new HTML_INPUT_TEXT(['name'=>'end','value'=>$tmpl['sb']->end,'option' => ['help_text' => '結束時間']]),
                        new HTML_INPUT_TEXT(['name'=>'users','value'=>implode(',',$tmpl['sb']->GetUsers()),'option' => ['help_text' => '登記ID']]),
                        new HTML_INPUT_TEXT(['name'=>'problems','value'=>implode(',',$p),'option' => ['help_text' => '題目列表']]),
                        new HTML_INPUT_DIV(['option' =>[
                            'help_text' => '公告',
                            'html'=>"<textarea id='announcement'>".\SKYOJ\html($tmpl['sb']->announce)."</textarea>",
                            'row'=>true]
                        ]),
                        new HTML_INPUT_BUTTOM(['name'=>'btn','title'=>'送出','option' => ['help_text' => 'true']]),
                    ]
                ]),'board');
            ?>
        </div><!--Main end-->
        <div class="col-lg-4">
            <h1>Advance&nbsp;<small><span id="adv-act-info"></span></small></h1>
            <p>
                <buttom class="btn btn-primary" adv-act="freeze">Freeze</buttom>
                凍結記分板 <small><span id="adv-act-freeze">重建並鎖定</span></small>
            </p>
            <?php if( $tmpl['sb']->state != 0 ): ?>
            <p>
                <buttom class="btn btn-danger" adv-act="close">Close</buttom>
                關閉記分板 <small><span id="adv-act-close">關閉記分板</span></small>
            </p>
            <?php endif; ?>
            <?php if( $tmpl['sb']->state != 1 ): ?>
            <p>
                <buttom class="btn btn-success" adv-act="open">Open</buttom>
                開啟記分板 <small><span id="adv-act-open">開啟記分板</span></small>
            </p>
            <?php endif; ?>
        </div>
    </div>
    <br>
     <!--<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table  table-bordered">
                <thead>
                    <tr>
                        <th>插件名稱</th>
                        <th>作者</th>
                        <th>版本</th>
                        <th>描述</th>
                        <th>格式</th>
                    </tr>
                </thead>
                </tbody>
                    <?php foreach ([] as $site => $data): ?>
                    <tr>
                        <td><?=$data['name']?></td>
                        <td><?=$data['author']?></td>
                        <td><?=$data['version']?></td>
                        <td><?=$data['desc']?></td>
                        <td><?=$data['format']?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>-->
</div>