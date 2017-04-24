<?php
if (!defined('IN_TEMPLATE')) {
    exit('Access denied');
}
?>
<script>
var rid=0;
function remrol(rid){
    $("[rid='"+rid+"']").remove();
}
function addrol(teamid,ptag){
    $("#main_b").append("<div class='col-xs-12' rid=\'"+rid+"\'><span class='glyphicon glyphicon-remove' onclick='remrol("+rid+")'></span> Team : "+teamid+" AC : " + ptag +"</div>");
    rid = rid +1;
}
$(document).ready(function(){
    var cont_id = <?=json_encode($tmpl['contest']->cont_id)?>;
    var last_time = 0;
    var delay = 1000;

    function Update(){
        $.get("<?=$SkyOJ->uri('contest','api','balloon')?>",{cont_id:cont_id,start:last_time},function(res){
            console.log(res.data.ac);
            if(res.status == 'error'){
                delay = delay * 2;
                if( delay > 60*1000 )delay = 60*1000;
            }else{
                delay = 1000;
                last_time = res.data.last;
                res.data.AC.forEach(function(e){
                    addrol(e.team,'P')
                    console.log(e);
                });
            }
            setTimeout(Update,delay);
        },"json").fail(function(e){
            delay = delay * 2;
            if( delay > 60*1000 )delay = 60*1000;
            console.log(e);
            setTimeout(Update,delay);
        });
    }
    Update();
});
</script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?=\SKYOJ\html($tmpl['contest']->title)?> <small>氣球發獎機</small></h1>
            <div></div>
            <div id="main_b"></div>
        </div>
    </div>
    <br>
</div>