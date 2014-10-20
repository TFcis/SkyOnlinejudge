<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class DB
{
    public $connect_data;
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
    static function timestamp()
    {
        return date('Y-m-d G:i:s');
    }
    static function query($query)
    {
        if( $stat = mysql_query($query) )
        {
            return $stat;
        }
        else
        {
            echo mysql_error().'<br>'.$query.'<br>';
            return false;
        }
    }
    
    static function fetch($stat)
    {
        if($res = mysql_fetch_array($stat)){
            return $res;
        }
        else{
            return false;
        }
    }
    
    static function cachefilepath($name)
    {
        global $_E;
        $path = $_E['ROOT']."/data/cache/$name.cache";
        return $path;
    }
    
    static function putcache($name ,$data ,$time = 5, $uid = 0)
    {
        $timeout = time()+$time*60;
        $cachetable = DB::tname('cache');
        $_SESSION['cache'][$name]['time'] = $timeout;
        $_SESSION['cache'][$name]['data'] = $data;
        $_SESSION['cache'][$name]['uid']  = $uid;
        
        if( $uid )
        {
            $data = addslashes(json_encode($data));
            DB::query("INSERT INTO $cachetable
                        (`name`, `timeout`,`data`)  VALUES
                        ('$uid+$name' ,'$timeout' ,'$data') 
                        ON DUPLICATE KEY UPDATE `data`= '$data'" );
        }
        else
        {
            $tmp  = array( 'time'=>$timeout , 'data'=>$data );
            $data = json_encode($tmp);
            $save = DB::cachefilepath($name);
            if( $handle = fopen($save,'w') )
            {
                fwrite($handle,$data);
                fclose($handle);
            }
        }
    }
    
    static function deletecache($name,$uid = 0)
    {
        if(isset($_SESSION['cache'][$name]))
            unset($_SESSION['cache'][$name]);
        if($uid)
            DB::query("DELETE FROM $cachetable WHERE `name` LIKE '$uid+$name'");
        else
        {
            $save = DB::cachefilepath($name);
            if( file_exists($save) )
            {
                unlink($save);
            }
        }
    }
    static function loadcache($name,$uid = 0)
    {
        $cachetable = DB::tname('cache');
        $time = time();
        $data = false;
        if( rand(1,300) == 1 )
        {
            DB::query("DELETE FROM $cachetable WHERE `timeout`<$time");
        }
        if($uid)
        {
            $res = DB::query(" SELECT `data` 
                                FROM  `$cachetable` 
                                WHERE `name` = '$uid+$name'
                                AND   `timeout` >= $time");
            if(!$res){
                return false;
            }
            $data = DB::fetch($res);
            $data = $data['data'];
            return json_decode($data,true);
        }
        else //Load by file
        {
            $save = DB::cachefilepath($name);
            if( file_exists($save) )
            {
                $handle=fopen($save,'r');
                $data=fgets($handle);
                fclose($handle);
                
                $data=json_decode($data,true);
                if( $data['time'] < $time )
                {
                    DB::deletecache($name);
                    return false;
                }
                return $data['data'];
            }
            else
            {
                return false;
            }
        }
        return false;
    }
    
    static function getuserdata( $table ,$uid = null )
    {
        $table = DB::tname($table);
        $resdata = array();
        
        if( !is_array($uid) )
        {
            $uid = array(intval($uid));
        }
        $uid =  implode(',', array_map('intval', $uid) );
        
        if( $res = DB::query("SELECT * FROM `$table` WHERE `uid` IN($uid);") )
        {
            while($sqldata = DB::fetch($res))
            {
                $resdata[$sqldata['uid']]=$sqldata;
            }
            return $resdata;
        }
        else
        {
            return false;
        }
    }
}

DB::connect();
?>