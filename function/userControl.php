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
    //this must call before use $_G[uid]
    static function registertoken($namespace,$timeleft)
    {
        global $_G;
        $table = DB::tname('usertoken');
        $token = md5(uniqid($namespace,true));
        $timeout = time() + $timeleft;
        
        $_SESSION[$namespace][$token]['timeout'] = $timeout;
        $_SESSION[$namespace][$token]['uid'] = $_G['uid'];
        setcookie($namespace,$token,$timeout);
        if($_G['uid'])
        {
            $id = $_G['uid'];
            if(!mysql_query("INSERT INTO `$table`".
                        "(`uid`, `timeout`, `type`, `token`)".
                        "VALUES ($id,$timeout,'$namespace','$token')"))
            {
                return false;
            }
        }
        return $token;
    }
    
    static function deletetoken($namespace)
    {
        global $_G;
        $table = DB::tname('usertoken');
        
        setcookie($namespace,'',0);
        if( isset( $_SESSION[$namespace] ) )
        {
            unset($_SESSION[$namespace]);
        }
        if($_G['uid'])
        {
            $id = $_G['uid'];
            mysql_query("DELETE FROM  `$table` ".
                        " WHERE  `uid` = $id ".
                        " AND  `type` = '$namespace'");
        }
    }
    
    static function checktoken($namespace)
    {
        global $_G;
        $table = DB::tname('usertoken');
        if( !isset($_COOKIE[$namespace]) || !isset($_COOKIE['uid']) )
        {
            return false;
        }
        
        $token = isset($_COOKIE[$namespace])?$_COOKIE[$namespace]:'';
        $uid   = isset($_COOKIE['uid'])?$_COOKIE['uid']:'';
        
        if( !preg_match('/^[a-z0-9]+$/',$token) ||
            !preg_match('/^[0-9]+$/',$uid))
        {
            return false;
        }
        
        if( isset($_SESSION[$namespace][$token]) )
        {
            if( $_SESSION[$namespace][$token]['uid'] == $uid )
                return true;
            else
                return false;
        }
        else{
            //Load form SQL
            if($sqlres = mysql_query("SELECT * FROM  `$table` ".
                                     " WHERE  `uid` = $uid ".
                                     " AND  `token` ='$token'"))
            {
                if( $sqldata = mysql_fetch_array($sqlres) )
                {
                    if( $sqldata['timeout']>time() )
                    {
                        return true;
                    }
                    else
                    {
                        mysql_query("DELETE FROM  `$table` ".
                                     " WHERE  `uid` = $uid".
                                     " AND  `token` ='$token'");
                        return false;
                    }
                }
                else //No data stroe in MYSQL
                {
                    return false;
                }
            }
            else // SQL error
            {
                return false;
            }
        }
        //protect
        return false;
    }
    
    static function intro()
    {
        global $_G,$permission;
        $acctable = DB::tname('account');
        if( userControl::checktoken('login') )
        {
            //load user data
            //$_COOKIE['uid'] is checked in userControl::checktoken
            $loginuid = $_COOKIE['uid'];
            if( DB::loadcache('login',$loginuid) )
            {
                //Load form cache
                $_G=DB::loadcache('login',$loginuid);
            }
            else
            {
                $sqlres=mysql_query("SELECT * FROM  `$acctable`".
                                    " WHERE  `uid` =  $loginuid");
                if( $sqldata = mysql_fetch_array($sqlres) )
                {
                    $_G = $sqldata;
                    DB::putcache('login',$_G,5,$loginuid);
                }
                else //sql error
                {
                    echo mysql_error();
                    exit(0);
                }
            }
        }
        else // guest
        {
             $_G = $permission['guest'];
        }
    }
    
    static function SetLoginToken($uid)
    {
        global $_G;
        $acctable = DB::tname('account');
        
        $sqlres=mysql_query("SELECT * FROM  `$acctable`".
                            " WHERE  `uid` =  $uid");
        if( $sqldata = mysql_fetch_array($sqlres) )
        {
            $_G['uid'] = $uid;
            userControl::registertoken('login',864000);
            // save $sqldata in cache
            DB::putcache('login', $sqldata ,10 ,$uid);
            setcookie('uid',$uid,time()+864000);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    static function DelLoginToken()
    {
        global $_G;
        DB::deletecache('login',$_G['uid']);
        userControl::deletetoken('login');
    }
    
    static function getuserdata( $table ,$uid = null )
    {
        $table = DB::tname($table);
    }
}