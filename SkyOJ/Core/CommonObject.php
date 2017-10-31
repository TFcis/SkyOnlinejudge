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
    
    protected $table; 
    protected $prime_key;
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

    public function Load(int $id)
    {
        if( !isset($this->table,$this->prime_key) )
        {
            throw new CommonObjectError($called,SKY_ERROR::UNKNOWN_ERROR);
        }
        $table = DB::tname($this->table);
        $data = DB::fetchEx("SELECT * FROM `{$table}` WHERE `{$this->prime_key}`=?",$id);
        if( empty($data) ) return false;
        $this->sqldata = $data;
        if( method_exists($this,'afterLoad') )
            return $this->afterLoad();
        return true;
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
        $table = DB::tname($this->table);
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
}