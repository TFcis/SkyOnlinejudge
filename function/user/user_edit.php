<?php

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//Only By Post Login Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('EDIT'))
    throwjson('error','Access denied');

$allowpage = array('ojacct','acct','authacct');

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
        
    case 'authacct':
        $authclass = safe_post('cls');
        $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
        if( !$authclass || !is_string($authclass) || !isset($class[$authclass]) )
        {
            throwjson('error','data error!');
        }
        $class = $class[$authclass];
        $user = new UserInfo($euid);
        if( !$user->is_registed() )
        {
            throwjson('error','user data error!');
        }
        $ojacct = $user->load_data('ojacct');
        if( !$ojacct[$authclass] )
        {
            throwjson('error','empty acct data!');
        }
        
        if( !method_exists($class,'authenticate') )
            throwjson('error','Not support authenticate!');
        $res = $class->authenticate($euid,$ojacct[$authclass]['account']);
        if( $res === true )
        {
            $ojacct[$authclass]['approve'] = 1;
            $indexid = $ojacct[$authclass]['indexid'] ;
            $user->save_data('ojacct',$ojacct,array(null,array($indexid)));
            throwjson('SUCC','Success');
        }
        throwjson ('error',$res[1]);
        break;
    default:
        throwjson('error','modifying',$ojacct);
        break;
}

throwjson('error','I dont know...');