<?php namespace SkyOJ\Core;
use SkyOJ\Core\DataBase\DB as DB;

class SKY_ERROR extends \SkyOJ\Helper\Enum
{
    const ERROR_NO = 0;

    const NO_SUCH_METHOD = 1;
    const NO_SUCH_ENUM_VALUE = 2;
    const NO_SUCH_DATA = 3;
    const UNKNOWN_ERROR = 9999;
}

class CommonObjectError extends \Exception
{
    public function __construct(string $msg,int $code = SKY_ERROR::UNKNOWN_ERROR , Exception $previous = null)
    {
        parent::__construct(SKY_ERROR::str($code).':'.$msg, $code, $previous);
    }
}

abstract class CommonObject{
    
    protected static $table; 
    protected static $prime_key;
    protected $sqldata = [];

    public function __get(string $name)
    {
        return $this->sqldata[$name];
    }

    public function __set(string $name,$var):void
    {
        $called = "checkSet".$name;
        if( !method_exists($this,$called) )
        {
            trigger_error("Set {$name} without Check is danger!");
        }
        elseif ( !$this->$called($var) )
        {
            throw new CommonObjectError($called,SKY_ERROR::UNKNOWN_ERROR);
        }
        $this->sqldata[$name] = $var;
    }

    public function load(int $id)
    {
        if( !isset(static::$table,static::$prime_key) )
        {
            throw new CommonObjectError($id,SKY_ERROR::UNKNOWN_ERROR);
        }
        $table = DB::tname(static::$table);
        $keyname = static::$prime_key;
        $data = DB::fetchEx("SELECT * FROM `{$table}` WHERE `{$keyname}`=?",$id);
        if( empty($data) ) return false;
        $this->sqldata = $data;
        if( method_exists($this,'afterLoad') )
            return $this->afterLoad();
        return true;
    }

    public function loadByData(array $data)
    {
        $this->sqldata = $data;
        if( method_exists($this,'afterLoad') )
            return $this->afterLoad();
        return true;
    }

    function loadRange(int $start,int $end)
    {
        if( !isset(static::$table,static::$prime_key) )
        {
            throw new CommonObjectError($called,SKY_ERROR::UNKNOWN_ERROR);
        }
        $table = DB::tname(static::$table);
        $keyname = static::$prime_key;
        $data = DB::fetchAllEx("SELECT * FROM `{$table}` WHERE `{$keyname}` BETWEEN  ? AND ?",$start,$end);
        $class = get_called_class();
        $res = [];
        foreach( $data as $row )
        {
            $p = new $class(null);
            if( $p->loadByData($row) );
                $res[] = $p;
        }
        return $res;
    }

    static function fetchColByPrimeID(int $id,string $col)
    {
        $table = DB::tname(static::$table);
        $keyname = static::$prime_key;

        return DB::fetchEx("SELECT `$col` FROM `{$table}` WHERE `{$keyname}`=?",$id);
    }

    protected function UpdateSQLLazy(string $col = null,$val = null)
    {
        static $host = [];
        if( $col === null ){
            $back = $host;
            $host = [];
            return $back;
        }
        $this->sqldata[$col] = $val;
        $host[] = [$col,$val];
    }

    public function save():bool
    {
        $table = DB::tname(static::$table);
        $prime_key = $this->prime_key;
        $data = $this->UpdateSQLLazy();

        if( empty($data) )
            return true;

        try{
            DB::$pdo->beginTransaction();
            $this->UpdateSQL_extend();
            foreach( $data as $d )
                if( \DB::queryEx("UPDATE `{$table}` SET `{$d[0]}`= ? WHERE `{$prime_key}`=?",$d[1],$this->$idname()) === false )
                    throw \DB::$last_exception;
            DB::$pdo->commit();
            return true;
        }catch(\Exception $e){
            DB::$pdo->rollBack();
            #\Log::msg(\Level::Error,"UpdateSQL Transaction rollBack! :",$e->getMessage());
            return false;
        }
    }

    abstract public function getObjLevel():int;
}