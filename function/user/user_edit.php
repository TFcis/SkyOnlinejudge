<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//Only By Post Login Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('EDIT'))
    throwjson('error','Access denied');

$allowpage = array('ojacct');
// may need a function instead isset(A)?A:''
$editpage = isset($_POST['page'])?$_POST['page']:'';
if(!in_array($editpage ,$allowpage))
    throwjson('error','No such page');
    
$euid = isset($_POST['id'])?$_POST['id']:'';
if(!preg_match('/^[0-9]+$/',$euid))
    throwjson('error','UID error');
$euid = (string)((int)$euid);
// for admin test!
if( $euid!=$_G['uid'] && $_G['uid']!= 1 )
    throwjson('error','not admin or owner');

switch($editpage)
{
    case 'ojacct':
        if( !isset($_E['ojlist']) )
        {
            //envadd('ojlist');
        }
        $argv = array();
        throwjson('error','zzz');
        break;
    default:
        throwjson('error','modifying');
        break;
}

throwjson('error','I dont know...');