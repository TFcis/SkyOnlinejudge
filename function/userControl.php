<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

$permission = array();
$permission['guest']['uid'] = "0";

$_G = $permission['guest'];

class userControl
{
    //this must call before use $_G
    static function intro()
    {
        global $_G;
        if( !isset($_COOKIE['token']) || !isset($_COOKIE['uid']) )
        {
            $_G = $permission['guest'];
            return ;
        }
        $token = $_COOKIE['token'];
        $uid   = $_COOKIE['uid'];
        
        if( isset($_SESSION['login'][$token]) )
        {
            if( $_SESSION['login'][$token]['uid'] == $uid )
            {
                //Load from cache
                $_G = $_SESSION['login'][$token];
                return ;
            }
            else
            {
                //May be hacked
                setcookie('token','',time()-6000);
                $_G = $permission['guest'];
                return ;
            }
        }
        else{
            //Load form SQL
            //need token ckeck table!
            $_G = $permission['guest'];
            return ;
        }
    }
    
    static function SetLoginToken($uid)
    {
        global $_config;
        $acctable = $_config['db']['tablepre'].'_account';
        
        $sqldata;
        $logintoken = md5(uniqid('login_',true));
        
        $sqlres=mysql_query("SELECT * FROM  `$acctable`".
                            "WHERE  `uid` =  $uid");
        if( $sqldata = mysql_fetch_array($sqlres) )
        {
            $_SESSION['login'][$logintoken] = $sqldata;
            setcookie('token',$logintoken,time()+3600);
            setcookie('uid',$uid,time()+3600);
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    static function DelLoginToken()
    {
        unset($_SESSION['login']);
        setcookie('token','',time()-6000);
        setcookie('uid','',time()-6000);
    }
}