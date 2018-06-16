<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
$(document).ready(function(){
    $("#testcase-zip-form").submit(function(e)
    {
        e.preventDefault();
        api_submit("<?=$SkyOJ->uri('problem','api','add_testcasezip')?>","#testcase-zip-form","#zip-dbg",function(e){
            location.reload();
        });
    });
});
function rmTestdata(){
    var pid = <?=$tmpl['problem']->pid?>;
    $.get("<?=$SkyOJ->uri('problem','api','rm_testcase')?>",{pid:pid},function(e){
        location.reload();
    });
}
function syncTestdata(){
    var pid = <?=$tmpl['problem']->pid?>;
    console.log(87);
    $.get("<?=$SkyOJ->uri('problem','api','sync_testcase')?>",{pid:pid},function(e){
        $("#misc-dbg").html(e.data);
    },"json").fail(function(e){
        console.log(e);
        $("#misc-dbg").html(e.responseText);
    });
}
</script>
<div class="container">
    <div class="row">
        <div class="col">
            <div id="accordion" role="tablist">
                <div class="card bg-dark">
                    <div class="card-header" role="tab" id="headingOne">
                        <h5 class="mb-0">
                            <a data-toggle="collapse" href="#collapseZip" role="button" aria-expanded="false" aria-controls="collapseZip">
                            Upload All Data
                            </a>
                        </h5>
                    </div>
                    <div id="collapseZip" class="collapse" role="tabpanel" aria-labelledby="collapseZip" data-parent="#accordion">
                        <div class="card-body">
                            <form class="form-inline" id="testcase-zip-form">
                                <input type='hidden' name="pid" value="<?=$tmpl['problem']->pid?>">
                                <div class="form-group">
                                    <label for="testdata-zip">Testdata Zip </label>
                                    <input type="file" class="form-control-file" id="testdata-zip" name="file" accept=".zip">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <span id="zip-dbg"></span>             
                            </form>
                            <hr>
                            <button type="submit" class="btn btn-danger" onclick="rmTestdata()">Remove All Tests</button>
                            <button type="submit" class="btn btn-info" onclick="syncTestdata()">Sync To Judge</button>
                            <span id="misc-dbg"></span>
                        </div>
                    </div>
                </div>
            </div><!-- end of collapseZip-->
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <div class="page-header">
                <h1>測試資料列表<small></small></h1>
                <span id="attach_dbg"></span>
            </div>
            <table class = "table table-dark">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Input</th>
                        <th scope="col">Output</th>
                        <th scope="col">Tools</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=0;foreach($tmpl['testdata'] as $row) :$i++;?>
                    <tr scope="row">
                        <td><?=$i?></td>
                        <td><?=\SKYOJ\html($row->input())?></td>
                        <td><?=\SKYOJ\html($row->output())?></td>
                        <td>
                            <span class="icon-bttn">
                                X
                            </span>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
