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
        api_submit("<?=$SkyOJ->uri('problem','api','add_attach')?>","#form-upload","dbg",function(){
            location.reload();
        });
    });
</script>
<span id="dbg"></span>
<div id = "image-bar"></div>
<div class="container">
    <div>
        <div class="page-header">
            <h1>附件列表<small></small></h1>
        </div>
        <table class = "table table-striped">
            <thead>
                <tr>
                    <th>File name</th>
                    <th>Size</th>
                    <th style='width: 140px'>
                        <form id="form-upload" action="<?=$SkyOJ->uri('problem','api','add_attach')?>">
                            <input id="file-upload" type="file" name="name" style="display: none;">
                            <input type="hidden" name="pid" value="<?=$tmpl['problem']->pid?>">
                        </form>
                        Add<a id="addfile" class="icon-bttn" title="Add File">
                            <span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($tmpl['attachs'] as $row) :?>
                <tr style = "height: 40px">
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
