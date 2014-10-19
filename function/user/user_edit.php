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
            $_E['ojlist'] = array();
            $tb = DB::tname('ojlist');
            if( $res = DB::query("SELECT * FROM `$tb`") )
            {
                while( $dat = DB::fetch($res) )
                {
                    $_E['ojlist'][]=$dat;
                }
            }
        }
        $argv = array();
        
        foreach($_E['ojlist'] as $oj)
        {
            if( isset( $_POST[$oj['class']] ) )
            {
                $argv[$oj['class']]=$_POST[$oj['class']];
            }
        }
        $res = modify_ojacct($argv,$euid);
        if( $res[0] )
            throwjson('SUCC','SUCC');
        else
            throwjson('error',$res[1]);
        break;
    default:
        throwjson('error','modifying');
        break;
}

throwjson('error','I dont know...');