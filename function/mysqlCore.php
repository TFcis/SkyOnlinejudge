<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class MQ
{
    public $connect_data;
    function __construct()
    {
        
    }
    static function connect()
    {
        global $_config;
        $connect_data = mysql_connect(  $_config['db']['dbhost'],
                                $_config['db']['dbuser'],
                                $_config['db']['dbpw']);
        if(!$connect_data){
            die('ERROR:'.mysql_error());
        }
        mysql_query("SET NAMES 'utf8'");
        mysql_select_db($_config['db']['dbname']);
    }
    static function tname($name)
    {
        global $_config;
        return  $_config['db']['tablepre']."_".$name;
    }
    
    static function putcache($name ,$data ,$time = 5, $uid = 0)
    {
        $timeout = time()+$time*60;
        $_SESSION['cache'][$name]['time'] = $timeout;
        $_SESSION['cache'][$name]['data'] = $data;
        $_SESSION['cache'][$name]['uid']  = $uid;
    }
    
    static function loadcache($name,$uid = 0)
    {
        if( isset($_SESSION['cache'][$name]) &&
            $_SESSION['cache'][$name]['uid'] == $uid &&
            $_SESSION['cache'][$name]['time'] >time() )
        {
            return $_SESSION['cache'][$name]['data'];
        }
        return false;
    }
}

MQ::connect();
?>