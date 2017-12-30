<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function(){
    $('#addfile').on('click', function() {
        $('#file-upload').trigger('click');
    });
    $('#file-upload').change(function() {
        var name = $(this).val();
        if( name.length == 0 ) return ;
        api_submit("<?=$SkyOJ->uri('problem','api','add_attach')?>","#form-upload","#attach_dbg",function(e){
            location.reload();
        });
    });
});
</script>
<div class="container">
    <div>
        <div class="page-header">
            <h1>附件列表<small></small></h1>
            <div>limit per file : <?=ini_get('upload_max_filesize')?></div>
            <span id="attach_dbg"></span>
        </div>
        <table class = "table table-dark">
            <thead>
                <tr>
                    <th scope="col">File name</th>
                    <th scope="col">Size</th>
                    <th scope="col">
                        <form id="form-upload" action="<?=$SkyOJ->uri('problem','api','add_attach')?>">
                            <input id="file-upload" type="file" name="name" style="display: none;">
                            <input type="hidden" name="pid" value="<?=$tmpl['problem']->pid?>">
                        </form>
                        Add<a id="addfile" class="icon-bttn" title="Add File">
                            +
                        </a>
                    </th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($tmpl['attachs'] as $row) :?>
                <tr scope="row">
                    <td><a href="<?=$SkyOJ->uri('problem','view',$tmpl['problem']->pid,$row[0])?>"><?=\SKYOJ\html($row[0]);?></a></td>
                    <td><?=\SKYOJ\human_filesize($row[1])?></td>
                    <td>
                        <span class="icon-bttn">
                            <span class="glyphicon glyphicon-trash" title="移除"></span>
                        </span>
                    </td>
                    <td><?=$row[2]?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
