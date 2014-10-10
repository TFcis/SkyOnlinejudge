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
    static function RegisterTokenInNamespace($namespace,$timeleft,$data = null)
    {
        global $_G;
        $table = MQ::tname('usertoken');
        $token = md5(uniqid($namespace,true));
        $timeout = time() + $timeleft;
        
        $_SESSION[$namespace][$token]['timeout'] = $timeout;
        $_SESSION[$namespace][$token]['data'] = $data;
        $_SESSION[$namespace][$token]['uid'] = $_G['uid'];
        setcookie($namespace,$token,$timeout);
        if($_G['uid'])
        {
            
            $id = $_G['uid'];
            $data = isset($data)?mysql_real_escape_string(json_encode($data)):"";
            mysql_query("INSERT INTO `$table`".
                        "(`uid`, `timeout`, `type`, `token`, `data`)".
                        "VALUES ($id,$timeout,'$namespace','$token','$data')");
        }
    }
    
    static function DeleteDataByNamespace($namespace)
    {
        global $_G;
        $table = MQ::tname('usertoken');
        
        setcookie($namespace,'',0);
        if( isset( $_SESSION[$namespace] ) )
            unset($_SESSION[$namespace]);
        if($_G['uid'])
        {
            $id = $_G['uid'];
            mysql_query("DELETE FROM  `$table` ".
                        "WHERE  `uid` = $id ".
                        "AND  `type` = '$namespace'");
        }
    }
    
    static function LoadDataByNamespace($namespace)
    {
        global $_G;
        $table = MQ::tname('usertoken');
        if( !isset($_COOKIE[$namespace]) || !isset( $_COOKIE['uid']) )
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
            //check if store data
            if( $_SESSION[$namespace][$token]['uid'] == $uid )
                return $_SESSION[$namespace][$token]['data'] ?
                        $_SESSION[$namespace][$token]['data'] : true;
            else
                return false;
        }
        else{
            //Load form SQL
            if($sqlres = mysql_query("SELECT * FROM  `$table` ".
                                     "WHERE  `uid` = $uid".
                                     "AND  `token` ='$token'"))
            {
                if( $sqldata = mysql_fetch_array($sqlres) )
                {
                    if( $sqldata['timeout']>time() )
                    {
                        return json_decode($sqldata['data']);
                    }
                    else
                    {
                        mysql_query("DELETE FROM  `$table` ".
                                     "WHERE  `uid` = $uid".
                                     "AND  `token` ='$token'");
                        return false;
                    }
                }
                else //No data stroe in MYSQL
                {
                    return true;
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
        if( $_G = userControl::LoadDataByNamespace('login') )
        {
            return ;
        }
        else
        {
             $_G = $permission['guest'];
             return ;
        }
    }
    
    static function SetLoginToken($uid)
    {
        global $_G;
        $acctable = MQ::tname('account');
        
        $sqlres=mysql_query("SELECT * FROM  `$acctable`".
                            "WHERE  `uid` =  $uid");
        if( $sqldata = mysql_fetch_array($sqlres) )
        {
            $_G['uid'] = $uid;
            userControl::RegisterTokenInNamespace('login',864000,$sqldata);
            //$_SESSION['login'][$logintoken] = $sqldata;
            //setcookie('token',$logintoken,time()+3600);
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
        userControl::DeleteDataByNamespace('login');
    }
}