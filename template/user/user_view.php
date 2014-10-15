<?php
if(!defined('IN_TEMPLATE'))
{
    exit('Access denied');
}
?>
<div class="jumbotron" id="jumbotron">
    <div class="container">
        <div class="col-md-3">
            <img src="<?=$_E['template']['avaterurl']?>" alt="" height='300px'>
        </div>
        <div class="col-md-7">
            <h1>[L]<?=$_E['template']['nickname']?><h1>
            <blockquote>
                <p><?=$_E['template']['quote']?></p>
            </blockquote>
        </div>
    </div>
</div>
<h1>MAKING</h1>
