<?php namespace SKYOJ;
/**
 * object helper
 * 2016 Sky Online Judge Project
 * By LFsWang
 *
 */
class SKY_ERROR extends BasicEnum
{
    const ERROR_NO = 0;

    const NO_SUCH_METHOD = 1;
    const NO_SUCH_ENUM_VALUE = 2;
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
    
    protected abstract function getTableName():string;
    protected abstract function getIDName():string;

    protected $sqldata = [];

    public function __get(string $name)
    {
        if( !array_key_exists($name,$this->sqldata) )
            throw new CommonObjectError($name,SKY_ERROR::UNKNOWN_ERROR);
        return $this->sqldata[$name];
    }

    public function __set(string $name,$var):void
    {
        $called = "set_".$name;
        if( method_exists($this,$called) )
        {
            if( $this->$called($var)!==true )
            {
                throw new CommonObjectError($called,SKY_ERROR::UNKNOWN_ERROR);
            }
        }
        else
        {
            throw new CommonObjectError($called,SKY_ERROR::NO_SUCH_METHOD);
        }
    }

    public function isIdfail()
    {
        $name = $this->getIDName();
        return $this->$name() <= 0;
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

    
    protected function UpdateSQL_extend(){}
    public function UpdateSQL():bool
    {
        $table = $this->getTableName();
        $idname = $this->getIDName();
        $data = $this->UpdateSQLLazy();

        if( empty($data) )
            return true;

        try{
            \DB::$pdo->beginTransaction();
            $this->UpdateSQL_extend();
            foreach( $data as $d )
                if( \DB::queryEx("UPDATE `{$table}` SET `{$d[0]}`= ? WHERE `{$idname}`=?",$d[1],$this->$idname()) === false )
                    throw \DB::$last_exception;
            \DB::$pdo->commit();
            return true;
        }catch(\Exception $e){
            \DB::$pdo->rollBack();
            \Log::msg(\Level::Error,"UpdateSQL Transaction rollBack! :",$e->getMessage());
            return false;
        }
    }
}