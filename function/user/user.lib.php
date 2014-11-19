<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

function passwordHash($resoure)
{
    $re = md5("ncid".md5($resoure));
    //$re = password_hash($resoure, PASSWORD_BCRYPT);
    return $re;
}
function getTimestamp()
{
    return date('Y-m-d G:i:s');
}
function register($email,$nickname,$password,$repeat)
{
    global $_E;
    global $_config;
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    
    $_E['template']['reg'] = array();
    $acctable = DB::tname('account');
    $timestamp = getTimestamp();
    $sqlres;
    
    if( !preg_match($pattern,$email) || !preg_match($pattern,$password) || $password!= $repeat ||
        $nickname !== mysql_real_escape_string($nickname))
    {
        //use language!
        $_E['template']['reg'] = '格式錯誤';
        return false;
    }
    
    $nickname = mysql_real_escape_string($nickname);
    $password = passwordHash($password);

    $sqlres = DB::query(  "SELECT * FROM  `$acctable`".
                            "WHERE  `email` =  '$email'");
    if(DB::fetch($sqlres))
    {
        $_E['template']['reg'] = '帳號已被註冊';
        return false;
    }
    if(!DB::query("INSERT INTO `".$_config['db']['dbname']."`.`$acctable` ".
                    "(`uid`, `email`, `passhash`, `nickname`, `timestamp`) ".
                    "VALUES (NULL, '$email', '$password', '$nickname', '$timestamp')"))
    {
        $_E['template']['reg'] = 'SQL error';
        return false;
    }
    return true;
}

function login($email,$password,$usenickname = false)
{
    global $_E;
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    
    $_E['template']['login'] = array();
    $acctable = DB::tname('account');
    $sqlres;
    $userdata = null;
    if( $usenickname )
    {
        $nick = $email;
        if($nick !== mysql_real_escape_string($nick))
        {
            $_E['template']['alert'] = '格式錯誤';
            return false;
        }
        $sqlres=DB::query("SELECT `email` FROM  `$acctable` ".
                            "WHERE  `nickname` =  '$nick' ");
        if($res = DB::fetch($sqlres) )
        {
            $email = $res['email'];
        }
        else
        {
            $_E['template']['alert'] = '無此暱稱';
            return false;
        }
    }
    if( !preg_match($pattern,$email) || !preg_match($pattern,$password) )
    {
        $_E['template']['alert'] = '帳密錯誤';
        return false;
    }
    //$password = passwordHash($password);
    
    $sqlres=DB::query("SELECT * FROM  `$acctable`".
                        "WHERE  `email` =  '$email'");
    if(! ($userdata = DB::fetch($sqlres)) )
    {
        $_E['template']['alert'] = '無此帳號';
        return false;
    }
    //$password = passwordHash($password);
    if( passwordHash($password)!=$userdata['passhash'] )
    //if( !password_verify($password, $userdata['passhash']) )
    {
        $_E['template']['alert'] = '密碼錯誤';
        return false;
    }
    return $userdata;
}

function prepareUserView($uid)
{
    global $_G;
    $opt = array();
    if($uid==$_G['uid']){
        $opt = $_G;
    }
    else if( $res = DB::getuserdata('account',$uid) ){
        //protect
        $res['passhash']='';
        $opt = $res[$uid];
    }
    else{
        return false;
    }
    return $opt;
}



function page_ojacct($uid)
{
    global $_E;
    $table_oj = DB::tname('ojlist');
    $_E['template']['oj']=array();
    
    if( !isset($_E['ojlist']) )
        if( !envadd('ojlist') )
            return false;
            
    $userojacctlist = DB::getuserdata('userojlist',$uid);
    
    if( isset($userojacctlist[$uid]) )
        $userojacctlist = ojid_reg($userojacctlist[$uid]['data']);
    else
        $userojacctlist = ojid_reg('');
    
    foreach($_E['ojlist'] as $oj)
    {
        $tmp = $oj;
        if( $userojacctlist[ $oj['class'] ]['acct']  )
        {
            $tmp['value'] = $userojacctlist[ $oj['class'] ]['acct'];
        }
        $_E['template']['oj'][] = $tmp;
    }
    
    return true;
}



function modify_ojacct($argv,$euid)
{
    global $_E;
    $table = DB::tname('userojlist');
    if( !isset($_E['ojlist']) )
    {   //envadd
        return array(false,'ENVERROR');
    }
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
    $uacct = DB::getuserdata('userojlist',$euid);
    if(!isset($uacct[$euid]))
        $uacct = '';
    else
        $uacct = $uacct[$euid]['data'];
    $uacct = ojid_reg($uacct);
    foreach($argv as $oj => $acct)
    {
        if( !empty($acct) && $class[$oj]->checkid($acct) )
        {
            $uacct[$oj]['acct'] = $acct;
            $uacct[$oj]['approve']=0;
        }
        else
        {
            return array(false,"Accout error :$oj");
        }
    }
    $uacct = addslashes(json_encode($uacct));
    if( DB::query("INSERT INTO $table
                    (`uid`,`data`) VALUES 
                    ($euid,'$uacct')
                    ON DUPLICATE KEY UPDATE `data`= '$uacct'"))
    {
        return array(true);
    }
    return array(false,'SQL ERROR');
}