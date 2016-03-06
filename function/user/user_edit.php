<?php

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
//Only By Post & Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::CheckToken('EDIT'))
    throwjson('error','Access denied');

$allowpage = array('ojacct','acct','authacct','quote');

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
    case 'quote':
        $user = new UserInfo($euid);
        $olddata = $user->load_data('view');
        
        $quote = safe_post('quote');
        $quote_ref = safe_post('quote_ref');
        if( $quote===false || $quote_ref===false )
            throwjson('error','data missing');
        if(($s=strlen($quote))>350)throwjson('error',"Quote too long!($s)");
        if(($s=strlen($quote_ref))>80)throwjson('error',"Quote ref too long!($s)");
        $olddata['quote'] = $quote;
        $olddata['quote_ref'] = $quote_ref;
        if( $user->save_data('view',$olddata) )
            throwjson('SUCC','SUCC');
        else
            throwjson('error','Something error...');
        break;
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
        $user = new UserInfo($euid);
        if( !$user->is_load() || !$user->is_registed() )
            throwjson('error','Cannot get user data');
        $data = $user->load_data('account');
        
        $oldpass = safe_post('oldpasswd');
        if( !password_verify($oldpass,$data['passhash']) )
            throwjson('error','Worng Old Password');
        
        #Change Old Password
        $newpass = safe_post('newpasswd');
        if( !empty($newpass) )
        {
            if( !CheckPasswordFormat($newpass) )
                throwjson('error','Password format error!');
            $data['passhash'] = GetPasswordHash($newpass);
        }
        
        #change Realname
        $realname = safe_post('realname');
        if( $realname != '' )
        {
            $realname = trim($realname);
            if( strlen($realname) > 9 )
            {
                throwjson('error','Realname 太長');
            }
            $data['realname'] = $realname;
        }
        
        if( !$user->save_data('account',$data) )
        {
            throwjson('error','Server error. Cannot save data!');
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