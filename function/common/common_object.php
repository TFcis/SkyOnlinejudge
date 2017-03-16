<?php namespace SKYOJ;
/**
 * object helper
 * 2016 Sky Online Judge Project
 * By LFsWang
 *
 */

abstract class CommonObject{
    
    protected abstract function getTableName():string;
    protected abstract function getIDName():string;

    protected $sqldata = [];

    public function __get($name)
    {
        return $this->sqldata[$name]??null;
    }

    public function isIdfail()
    {
        $name = $this->getIDName();
        return $this->$name <= 0;
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