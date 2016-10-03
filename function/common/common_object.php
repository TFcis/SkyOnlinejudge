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
    
    function __destruct()
    {
        $this->UpdateSQL();
    }

    protected function UpdateSQLLazy(string $col = null,$val = null)
    {
        static $host = [];
        if( $col === null ){
            $back = $host;
            $host = [];
            return $back;
        }
        $this->SQLData[$col] = $val;
        $host[] = [$col,$val];
    }

    public function UpdateSQL():bool
    {
        $table = $this->getTableName();
        $idname = $this->getIDName();
        $data = $this->UpdateSQLLazy();

        //TODO : Need report sql status
        foreach( $data as $d )
            \DB::queryEx("UPDATE `{$table}` SET `{$d[0]}`= ? WHERE `{idname}`=?",$d[1],$this->$idname());
        return true;
    }
}