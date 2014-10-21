<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

if(!$_G['uid'])
{
    throwjson('error','nologin');
}
if(! isset($_POST['mod']) )
{
    throwjson('error','post');
}

$id = isset($_POST['id'])?$_POST['id']:'';

throwjson('error','error');