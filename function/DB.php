<?php
/*
 * DB Core
 * 2016 Sky Online Judge Project
 */
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

class DB{
    static $pdo = null;
    
    static function getpdo(){
        global $_config;
        $pdo = null;
        
        $db = $_config['db'];
        
        try {
            $pdo = new PDO($db['query_string'],$db['dbuser'],$db['dbpassword']);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $pdo;
    }
    
    //Get Real Table name
    static function tname(string $table)
    {
        global $_config;
        return $_config['db']['tablepre'].$table;
    }
    static public function genQuestListSign(int $num):string
    {
        if( $num < 0 )
        {
            DB::log("genQuestListSign num<0");
            $num = 0;
        }
        return implode(',',array_fill(0,$num,'?'));
    }

    //初始化PDO
    static function intro()
    {
        global $_config;
        self::$pdo = DB::getpdo();
    }
    
    static function prepare(string $string)
    {
        try {
            $res = self::$pdo->prepare($string);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/> $string <br/>";
            die();
        }
        return $res;
    }
    
    static function execute( $object,$array=array() )
    {
        try{
            if( !$object->execute( $array ) )
            {
                $data = $object->errorInfo();
                DB::log( $data[2].",\nDB execute String : " . $object->queryString );
                return false;
            }
        } catch (PDOException $e) {
            DB::log( 'DB Exception :'.$e->getMessage().",\nString :".$object->queryString );
            return false;
        }
        return true;
    }
    
    private static function log(string $description)
    {
        $table = DB::tname('syslog');
        $res = DB::prepare("INSERT INTO `$table`(`id`, `timestamp`, `level`, `message`) VALUES (NULL,NULL,?,?)");
        if( !$res->execute( array(-1,$description) ) )
        {
            die('System crash! Please call admin to fix');
        }
        return true;
    }
    
    static function query( string $str,$val=array() )
    {
        $res = DB::prepare($str);
        if( DB::execute($res,$val) )
            return $res;
        return false;
    }
    
    static function queryEx( string $str,...$vals )
    {
        $res = DB::prepare($str);
        if( DB::execute($res,$vals) )
            return $res;
        return false;
    }
    
    static function fetch( string $str,$val=array() )
    {
        if( $res = DB::query($str,$val) )
            return $res->fetch();
        return false;
    }
    
    static function fetchEx( string $str,...$vals )
    {
        if( $res = DB::query($str,$vals) )
            return $res->fetch();
        return false;
    }
    
    static function fetchAll( string $str,$val=array() )
    {
        if( $res = DB::query($str,$val) )
            return $res->fetchAll();
        return false;
    }
    
    static function fetchAllEx( string $str,...$vals )
    {
        if( $res = DB::query($str,$vals) )
            return $res->fetchAll();
        return false;
    }
    
    static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }
    
    /**
     *  ArrayToQueryString
     *  Make data like
     *  [
     *      'account' => 'aa',
     *      0 => 'aa',  //will be remove
     *      'data'    => 'lala'
     *      1 => 'lala',//will be remove
     *  ]
     *  to : 
     *  [
     *      'column' => "`account`,`data`"
     *      'query'  => ":account,:data"
     *      'update' => "`account`=:account,`data`=:data"
     *      'data'   => [':account'=>'aa',':data' => 'lala']
     *  ]
     */
    static function ArrayToQueryString(array $d)
    {
        $res = ['column'=>'','query'=>'','update'=>'','data'=>[]];
        foreach( $d as $i =>$v )
        {
            if( !is_int($i) )
            {
                $res['column'].= "`$i`,";
                $res['query'] .= ":$i,";
                $res['update'].= "`$i`=:$i,";
                $res['data'][":$i"] = $v;
            }
        }
        $res['column'] = rtrim($res['column'],",");
        $res['query']  = rtrim($res['query'],",");
        $res['update'] = rtrim($res['update'],",");
        return $res;
    }
}