<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class SQL{
    static $pdo = null;
        
    static function getpdo(){
        global $_config;
        $_config['db']['querystring']="mysql:host={$_config['db']['dbhost']};dbname={$_config['db']['dbname']}";
        $pdo = null;
        try {
            $pdo = new PDO($_config['db']['querystring'],$_config['db']['dbuser'] ,$_config['db']['dbpw'] );
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $pdo;
    }
    
    static function connect()
    {
        global $pdo;
        global $_DB;
        self::$pdo = SQL::getpdo();
    }
    
    static function prepare($string)
    {
        try {
            $res = self::$pdo->prepare($string);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/> $string <br/>";
            die();
        }
        return $res;
    }
    
    static function tname($name)
    {
        global $_config;
        return  $_config['db']['tablepre']."_".$name;
    }
    
    static function log($namespace,$description)
    {
        $table = SQL::tname('syslog');
        $res = SQL::prepare("INSERT INTO `$table`(`id`,`timestamp`, `namespace`, `description`) VALUES (NULL,NULL,?,?)");
        if( !$res->execute( array($namespace,$description) ) )
        {
            die('System crash! Please call admin to fix');
        }
        return true;
    }
    
    static function execute( $object , $array = array() )
    {
        try{
            if( !$object->execute( $array ) )
            {
                $data = $object->errorInfo();
                SQL::log('SQL execute', $data[2] .", \nString : " . $object->queryString );
                return false;
            }
        } catch (PDOException $e) {
            SQL::log('SQL Exception', $e->getMessage() .", \nString : " . $object->queryString );
            return false;
        }
        return true;
    }
    
    static function query( $str , $val = array() )
    {
        $res = SQL::prepare($str);
        if( SQL::execute($res,$val) )
            return $res;
        return false;
    }
    
    static function fetch( $str , $val = array() )
    {
        if( $res = SQL::query($str,$val) )
            return $res->fetch();
        return false;
    }
    
    static function fetchAll( $str , $val = array() )
    {
        if( $res = SQL::query($str,$val) )
            return $res->fetchAll();
        return false;
    }
    
    static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }
}