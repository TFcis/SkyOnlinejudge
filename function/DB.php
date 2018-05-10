<?php
/*
 * DB Core
 * 2016 Sky Online Judge Project
 */
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class DB
{
    public static $pdo = null;
    public static $last_exception = null;

    public static function getpdo()
    {
        global $_config;
        $pdo = null;

        $db = $_config['db'];

        try {
            $pdo = new PDO($db['query_string'].";dbname=$db[dbname]", $db['dbuser'], $db['dbpassword']);
        } catch (PDOException $e) {
            echo 'Error!: '.$e->getMessage().'<br/>';
            die();
        }

        return $pdo;
    }

    //Get Real Table name
    public static function tname(string $table)
    {
        global $_config;

        return $_config['db']['tablepre'].$table;
    }

    public static function genQuestListSign(int $num):string
    {
        if ($num < 0) {
            self::log('genQuestListSign num<0');
            $num = 0;
        }

        return implode(',', array_fill(0, $num, '?'));
    }

    //初始化PDO
    public static function intro()
    {
        global $_config;
        self::$pdo = self::getpdo();
    }

    public static function prepare(string $string)
    {
        try {
            $res = self::$pdo->prepare($string);
        } catch (PDOException $e) {
            self::$last_exception = $e;
            echo 'Error!: '.$e->getMessage()."<br/> $string <br/>";
            die();
        }

        return $res;
    }

    public static function execute($object, $array = [])
    {
        try {
            if (!$object->execute($array)) {
                $data = $object->errorInfo();
                self::$last_exception = new \Exception($data[2]);
                self::log($data[2].",\nDB execute String : ".$object->queryString);
                return false;
            }
        } catch (PDOException $e) {
            self::$last_exception = $e;
            self::log('DB Exception :'.$e->getMessage().",\nString :".$object->queryString);
            return false;
        }

        return true;
    }

    private static function log(string $description)
    {
        $table = self::tname('syslog');
        $res = self::prepare("INSERT INTO `$table`(`id`, `timestamp`, `level`, `message`) VALUES (NULL,NULL,?,?)");
        if (!$res->execute([-1, $description])) {
            die('System crash! Please call admin to fix');
        }

        return true;
    }

    public static function query(string $str, $val = [])
    {
        $res = self::prepare($str);
        if (self::execute($res, $val)) {
            return $res;
        }

        return false;
    }

    public static function queryEx(string $str, ...$vals)
    {
        $res = self::prepare($str);
        if (self::execute($res, $vals)) {
            return $res;
        }

        return false;
    }

    public static function fetch(string $str, $val = [])
    {
        if ($res = self::query($str, $val)) {
            return $res->fetch();
        }

        return false;
    }

    public static function fetchEx(string $str, ...$vals)
    {
        if ($res = self::query($str, $vals)) {
            return $res->fetch();
        }

        return false;
    }

    public static function fetchAll(string $str, $val = [])
    {
        if ($res = self::query($str, $val)) {
            return $res->fetchAll();
        }

        return false;
    }

    public static function fetchAllEx(string $str, ...$vals)
    {
        if ($res = self::query($str, $vals)) {
            return $res->fetchAll();
        }

        return false;
    }

    public static function lastInsertId(string $name = null)
    {
        if( $name===null )
            \Log::msg(\Level::Warning,'DB::lastInsertId required a identity for cross SQL like PGSQL');
        return self::$pdo->lastInsertId($name);
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
     *  ].
     */
    public static function ArrayToQueryString(array $d)
    {
        $res = ['column' => '', 'query' => '', 'update' => '', 'data' => []];
        foreach ($d as $i => $v) {
            if (!is_int($i)) {
                $res['column'] .= "`$i`,";
                $res['query'] .= ":$i,";
                $res['update'] .= "`$i`=:$i,";
                $res['data'][":$i"] = $v;
            }
        }
        $res['column'] = rtrim($res['column'], ',');
        $res['query'] = rtrim($res['query'], ',');
        $res['update'] = rtrim($res['update'], ',');

        return $res;
    }
}
