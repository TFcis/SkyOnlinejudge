<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class DB
{
    static public $con;
    static function connect()
    {
        global $_config;
        self::$con = mysqli_connect(  $_config['db']['dbhost'],
                                $_config['db']['dbuser'],
                                $_config['db']['dbpw']);
        if( mysqli_connect_errno() ){
            die('ERROR:'.mysql_error());
        }
        mysqli_query(self::$con,"SET NAMES 'utf8'");
        mysqli_select_db(self::$con,$_config['db']['dbname']);
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
        if( $stat = mysqli_query(self::$con,$query) )
        {
            return $stat;
        }
        else
        {
            Render::errormessage(mysqli_error(self::$con),'SQL Core');
            return false;
        }
    }
    
    static function fetch($stat)
    {
        if($res = mysqli_fetch_array($stat)){
            return $res;
        }
        else{
            return false;
        }
    }
    
    static function countrow($table,$rule = null)
    {
        $table = DB::tname($table);
        $match = '';
        if( $rule !== null )
            $match = ' WHERE '.$rule;
        $res = DB::query("SELECT COUNT(1) FROM `$table` $match");
        $res = DB::fetch($res);
        return $res[0];
    }
    
    static function cachefilepath($name)
    {
        global $_E;
        $path = $_E['ROOT']."/data/cache/$name.cache";
        return $path;
    }

    static function putcache($name ,$data ,$time = 5, $uid = 0)
    {
        if($time === 'forever')
            $timeout = PHP_INT_MAX ;
        else
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
    static function loadcache( $name,$uid = 0)
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
    
    static function getuserdata( $table ,$uid = null ,$data ='*')
    {
        $table = DB::tname($table);
        $resdata = array();
        
        if( !is_array($uid) )
        {
            $uid = array(intval($uid));
        }
        $uid =  implode(',', array_map('intval', $uid) );
        
        if( empty($uid) )
        {
            return array();
        }
        
        if( $res = DB::query("SELECT $data FROM `$table` WHERE `uid` IN($uid);") )
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