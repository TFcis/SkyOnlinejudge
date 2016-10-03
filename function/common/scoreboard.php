<?php namespace SKYOJ;
/**
 * scoreboard
 * 2016 Sky Online Judge Project
 * By LFsWang
 *
 */


class ScoreBoardTypeEnum extends BasicEnum
{
    const ScoreBoard = 1;
}
require_once 'common_object.php';
class ScoreBoard extends CommonObject
{
    private $sb_id;
    private $sb_data;
    private $sb_users;

    protected function getTableName():string
    {
        static $t;
        if( isset($t) )return $t;
        return $t = \DB::tname('problem');
    }

    protected function getIDName():string
    {
        return 'sb_id';
    }

    function __construct(int $sb_id)
    {
        try{
            $tscoreboard = \DB::tname('scoreboard');

            $data = \DB::fetchEx("SELECT * FROM `{$tscoreboard}` WHERE `sb_id` = ?",$sb_id);
            if( $data===false )
            {
                throw new \Exception('Load Problem Failed!');
            }

            $this->sb_id = $sb_id;
            $this->sb_data = $data;
        }catch(\Exception $e){
            $this->sb_id = false;
        }
    }

    function sb_id()
    {
        return $this->sb_id;
    }

    function GetTitle():string
    {
        return $this->sb_data['title']??'(null)';
    }

    function SetTitle(string $title):bool
    {
        if( strlen($title)>100 )
        {
            return false;
        }
        $this->UpdateSQLLazy('title',$title);
    }
    
    function GetState():int
    {
        return $this->sb_data['state']??0;
    }

    function owner():int
    {
        return $this->sb_data['owner'];
    }

    private function load_userdata():bool
    {
        if( isset($this->sb_users) )
        {
            return true;   
        }
        if( $this->sb_id() === false )
        {
            return false;
        }

        $tscoreboard_user = DB::tname('scoreboard_user');
        $data = \DB::fetchAllEx("SELECT `uid`,`score` FROM {$tscoreboard_user} WHERE `sbid=?`",$this->sb_id());
        if( $data === false )
        {
            return false;
        }

        $this->sb_users = [];
        foreach($data as $row)
        {
            $this->sb_users[$row['uid']] = (double)$row['score'];
        }
        return true;
    }

    function GetUsers():array
    {
        if( !$this->load_userdata() )
            throw new \Exception('ScoreBoard getUsers Failed!');
        return $this->sb_users;
    }

    static function GetData(int $sb_id)
    {
        $tstatsboard = DB::tname('statsboard');
        return DB::fetchEx("SELECT * FROM `{$tstatsboard}` WHERE `id` = ?",$sb_id);
    }

    static function GetSBDTable(int $sb_id):string
    {
        static $exists = [];
        static $SIZE = 1000;
        $table_id = intdiv($sb_id,$SIZE);
        $tname = \DB::tname('scoreboard_data_'.$table_id);
        $texname = \DB::tname('scoreboard_data_example');

        if( !isset($exists[$tname])  && \DB::query("select 1 from `{$tname}` LIMIT 1")===false )
        {
            if( \DB::query("CREATE TABLE `{$tname}` LIKE  `{$texname}`") === false )
            {
                throw new \Exception('Can not Create New Table for scoreboard!');
            }
        }
        $exists[$tname] = 1; //Anyway
        return $tname;
    }

    static function CreateNew(string $title,int $type):int
    {
        global $_G;
        $tstatsboard = \DB::tname('scoreboard');
        $res = \DB::queryEx("INSERT INTO `{$tstatsboard}`
            (`sb_id`, `title`, `owner`, `type`, `type_note`, `timestamp`) 
            VALUES (NULL,?,?,?,NULL,NULL)",
                       $title,$_G['uid'],$type);
        if( $res===false )
            throw new \Exception('SQL Error!');
        $sb_id = \DB::lastInsertId('sb_id');
        return $sb_id;
    }
}
