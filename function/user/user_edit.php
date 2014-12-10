<?php

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//Only By Post Login Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('EDIT'))
    throwjson('error','Access denied');

$allowpage = array('ojacct','acct');
// may need a function instead isset(A)?A:''
$editpage = safe_post('page');
if(!in_array($editpage ,$allowpage))
    throwjson('error','No such page');
    
$euid = safe_post('id');
if(!is_string($euid) || !preg_match('/^[0-9]+$/',$euid))
    throwjson('error','UID error');
$euid = (string)((int)$euid);
// for admin test!
if( !userControl::getpermission($euid) )
    throwjson('error','not admin or owner');
switch($editpage)
{
    case 'ojacct':
        if( !isset($_E['ojlist']) )
        {
            envadd('ojlist');
        }
        $argv = array();
        
        foreach($_E['ojlist'] as $oj)
        {
            if( ($val = safe_post($oj['class'])) !== false )
            {
                $argv[$oj['class']]=$val;
            }
        }
        $res = modify_ojacct($argv,$euid);
        if( $res[0] )
            throwjson('SUCC','SUCC');
        else
            throwjson('error',$res[1]);
        break;
    case 'acct' :
        $data = DB::getuserdata('account',$euid);
        if( empty($data) )
            throwjson('error','Cannot get user data');
            
        $oldpass = safe_post('oldpasswd');
        $newpass = safe_post('newpasswd');
        
        if( !$oldpass || !$newpass )
            throwjson('error','Empty Password!');
        if( !checkpassword($oldpass) || !checkpassword($newpass) )
            throwjson('error','Password format error!');
            
        $oldpass = passwordHash($oldpass);
        $newpass = passwordHash($newpass);

        if( $data[$euid]['passhash'] !== $oldpass )
            throwjson('error','Worng Old Password!');
        
        $table = DB::tname('account');
        if(!DB::query("UPDATE  `$table` SET  `passhash` = '$newpass' 
                    WHERE  `uid` =$euid;"))
        {
            throwjson('error','SQL Error!');
        }
        throwjson('SUCC','modify');
        break;
    default:
        throwjson('error','modifying');
        break;
}

throwjson('error','I dont know...');