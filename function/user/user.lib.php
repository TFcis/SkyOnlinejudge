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

function checkpassword($pass)
{
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    if( !is_string($pass) )
        return false;
    return preg_match($pattern,$pass);
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
    
    if( !preg_match($pattern,$email) || !checkpassword($password) || $password!= $repeat ||
        $nickname !== addslashes($nickname))
    {
        //use language!
        $_E['template']['reg'] = '格式錯誤';
        return false;
    }
    
    $nickname = addslashes($nickname);
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
    $resultdata = array(false,'');
    if( $usenickname )
    {
        $nick = $email;
        if($nick !== addslashes($nick))
        {
            $resultdata[1] = '格式錯誤';
            return $resultdata;
        }
        $sqlres=DB::query("SELECT `email` FROM  `$acctable` ".
                            "WHERE  `nickname` =  '$nick' ");
        if($res = DB::fetch($sqlres) )
        {
            $email = $res['email'];
        }
        else
        {
            $resultdata[1] = '無此暱稱';
            return $resultdata;
        }
    }
    if( !preg_match($pattern,$email) || !checkpassword($password) )
    {
        $resultdata[1] = '帳密錯誤';
        return $resultdata;
    }
    //$password = passwordHash($password);
    
    $sqlres=DB::query("SELECT * FROM  `$acctable`".
                        "WHERE  `email` =  '$email'");
    if(! ($userdata = DB::fetch($sqlres)) )
    {
        $resultdata[1] = '無此帳號';
        return $resultdata;
    }
    //$password = passwordHash($password);
    if( passwordHash($password)!=$userdata['passhash'] )
    //if( !password_verify($password, $userdata['passhash']) )
    {
        $resultdata[1] = '密碼錯誤';
        return $resultdata;
    }
    $resultdata[0]=true;
    $resultdata[1]=$userdata;
    return $resultdata;
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
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
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
        $tmp['info'] = '';
        $tmp['user'] = $userojacctlist[ $oj['class'] ];
        $tmp['c'] = $class[ $oj['class'] ];
        if( $tmp['user']['acct'] )
        {
            if( $tmp['user']['approve'] == 0 ) // No Check
            {
                $tmp['info'] = '尚未認證' ;
            }
            else
            {
                if( method_exists( $class[ $oj['class'] ] , 'account_detail' ) )
                {
                    $tmp['info'] = $class[ $oj['class'] ]->account_detail($tmp['user']['acct']);
                    if( !$tmp['info'] ) $tmp['info'] = '';
                    $tmp['info'] = "已驗證 ".$tmp['info'];
                }
            }
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
    {
        envadd('ojlist');
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
        if( !empty($acct) )
        {
            if( $uacct[$oj]['approve'] ==0 && $class[$oj]->checkid($acct) )
            {
                $uacct[$oj]['acct'] = $acct;
                $uacct[$oj]['approve']=0;
            }
            else
            {
                return array(false,"Accout error :$oj");
            }
        }
    }
    if( save_ojacct($euid,$uacct) )
        return array(true);
    return array(false,'SQL ERROR');
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

function save_ojacct($uid,$res)
{
    $table = DB::tname('userojlist');
    $res = addslashes(json_encode($res));
    if( DB::query("INSERT INTO $table
                    (`uid`,`data`) VALUES 
                    ($uid,'$res')
                    ON DUPLICATE KEY UPDATE `data`= '$res'"))
    {
        return true;
    }
    return false;
    
}