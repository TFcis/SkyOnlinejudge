<?php namespace SKYOJ;

if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
/**
 * @file contest.php
 * @brief Contest System Interface
 *
 * @author LFsWang
 * @copyright 2016 Sky Online Judge Project
 */

class ContestUserRegisterStateEnum extends BasicEnum
{
    const NotAllow  = 0; //< Only admin allow add user to contest
    const Open      = 1; //< All user without guest can join contest within time limit
    const PermitRequired = 2; //< require admin check

    static function allow(int $Case):bool
    {
        switch($Case)
        {
            case self::Open:
            case self::PermitRequired:
                return true;
            default:
                return false;
        }
    }
}

class ContestTeamStateEnum extends BasicEnum
{
    const NoRegister = 0; //< For programming, mean not register
    const Pending    = 1; //< Wait for admin permit
    const Accept     = 2; //< Normal Team
    const Hidden     = 3; //< Unlist Hidden Team for test contest
    const Reject     = 4; //< Reject
    const Unofficial = 5; //< list but not get award on scoreboard
    const Virtual    = 10;//< Join via virtual contest system
    const Dropped    = 99;//< may be some guy use hack!?

    static function allow(int $Case):bool
    {
        switch($Case)
        {
            case self::Accept:
            case self::Hidden:
            case self::Unofficial:
            case self::Virtual:
                return true;
            default:
                return false;
        }
    }
}

class ContestProblemStateEnum extends BasicEnum
{
    const Hidden  = 0;
    const Normal  = 1;
    const Readonly= 2;
    static function allow(int $Case):bool
    {
        switch($Case)
        {
            case self::Normal:
            case self::Readonly:
                return true;
            default:
                return false;
        }
    }
}

class ContestProblemInfo
{
    static $column=['cont_id','pid','ptag','state','priority'];
    public $cont_id;
    public $pid;
    public $ptag;
    public $state;
    public $priority;
}

class Contest extends CommonObject
{
    private $cont_id;
    private $now_time;
    protected function getTableName():string
    {
        static $t;
        if( isset($t) )return $t;
        return $t = \DB::tname('contest');
    }

    protected function getIDName():string
    {
        return 'cont_id';
    }

    function __construct(int $cont_id)
    {
        $data = \DB::fetchEx("SELECT * FROM {$this->getTableName()} WHERE `{$this->getIDName()}`=?",$cont_id);
        $this->now_time = \SKYOJ\get_timestamp(time());
        if( $data === false )
        {
            $this->cont_id = -1;
            $this->sqldata = [];
        }
        else
        {
            $this->cont_id = $data[$this->getIDName()];
            $this->sqldata = $data;
        }
    }
    
    function cont_id():int
    {
        return $this->cont_id;
    }

    //User check
    static function user_regstate_static(int $uid,int $cont_id):int
    {
        $table = \DB::tname("contest_user");
        $res = \DB::fetchEx("SELECT `state` FROM `{$table}` WHERE `cont_id`=? AND `uid`=?",$cont_id,$uid);
        return $res['state']??ContestTeamStateEnum::NoRegister;
    }

    function user_regstate(int $uid):int
    {
        return self::user_regstate_static($uid,$this->cont_id());
    }

    // preparing - (st) - play - (ed) ended
    function isended():bool
    {
        return strtotime($this->endtime) < strtotime($this->now_time);
    }

    function ispreparing():bool
    {
        return strtotime($this->now_time) < strtotime($this->starttime);
    }

    function isplaying():bool
    {
        return !$this->isended()&&!$this->ispreparing();
    }

    //problem function
    function get_all_problems_info():array
    {
        static $cache;
        if( isset($cache) )
        {
            return $cache;
        }
        $table = \DB::tname('contest_problem');
        $probs = \DB::fetchAllEx("SELECT * FROM {$table} WHERE `cont_id`=? ORDER BY `priority` ASC",$this->cont_id());
        if( $probs===false )
        {
            throw new \Exception('contest get_all_problems_info() fail!');
        }
        $data = [];
        foreach( $probs as $row )
        {
            $tmp = new ContestProblemInfo();
            foreach( ContestProblemInfo::$column as $c )
            {
                $tmp->$c = $row[$c];
            }
            $data[]=$tmp;
        }
        return $cache = $data;
    }
}