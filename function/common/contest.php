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

class ContestUserInfo
{
    static $column=['cont_id','uid','team_id','state'];
    public $cont_id;
    public $uid;
    public $team_id;
    public $state;
}

class ScoreBlock
{
    public $try_times;
    public $ac_time;
    public $is_ac;
    public $firstblood;
}

class UserBlock
{
    public $uid;
    public $total_submit;
    public $ac;
    public $ac_time;
    static function acm_cmp($a,$b){
        if( $a->ac!=$b->ac ) return $b->ac<=>$a->ac;
        if( $a->ac_time!=$b->ac_time ) return $a->ac_time<=>$b->ac_time;
        return $a->total_submit<=>$b->total_submit;
    }
}
class ProblemBlock
{
    public $pid;
    public $ptag;
    public $try_times;
    public $ac_times;
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

    function get_all_users_info():array
    {
        $table = \DB::tname('contest_user');
        $users = \DB::fetchAllEx("SELECT * FROM {$table} WHERE `cont_id`=?",$this->cont_id());
        if( $users===false )
        {
            throw new \Exception('contest get_all_users_info() fail!');
        }
        $data = [];
        foreach( $users as $row )
        {
            $tmp = new ContestUserInfo();
            foreach( ContestUserInfo::$column as $c )
            {
                $tmp->$c = $row[$c];
            }
            $data[]=$tmp;
        }
        return $data;
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
        return $data;
    }

    //ScoreBoard
    private function get_chal_data_by_timestamp($start,$end):array
    {
        $tname = \DB::tname('challenge');
        $tuid  = \DB::tname('contest_user');
        $tpid  = \DB::tname('contest_problem');
        $all = \DB::fetchAllEx("SELECT `pid`,`uid`,`result`,`timestamp` FROM $tname 
            WHERE  `timestamp` BETWEEN ? AND ? 
                AND `uid` IN (SELECT `uid` FROM $tuid WHERE `cont_id`=?) 
                AND `pid` IN (SELECT `pid` FROM $tpid WHERE `cont_id`=?) 
            ORDER BY `cid` ASC",
            $start,$end,$this->cont_id(),$this->cont_id()
        );

        return $all;
    }

    private function get_scoreboard_by_timestamp($start,$end)
    {
        $all  = $this->get_chal_data_by_timestamp($start,$end);
        $uids = $this->get_all_users_info();
        $pids = $this->get_all_problems_info();
        $scoreboard =[];
        $userinfo   =[];
        $probleminfo=[];
        $probleminfo_build = false;

        foreach($uids as $user)
        {
            $uid=$user->uid;
            $userinfo[$uid] = new UserBlock();
            $userinfo[$uid]->uid=$uid;
            $userinfo[$uid]->total_submit=0;
            $userinfo[$uid]->ac=0;
            $userinfo[$uid]->ac_time=0;

            $scoreboard[$uid]=[];
            foreach($pids as $row)
            {
                $pid=$row->pid;
                $scoreboard[$uid][$pid]=new ScoreBlock();
                $scoreboard[$uid][$pid]->try_times = 0;
                $scoreboard[$uid][$pid]->is_ac     = 0;
                $scoreboard[$uid][$pid]->ac_time   = 0;
                $scoreboard[$uid][$pid]->firstblood= 0;
                if( !$probleminfo_build )
                {
                    $probleminfo[$pid] = new ProblemBlock();
                    $probleminfo[$pid]->pid = $pid;
                    $probleminfo[$pid]->ptag = $row->ptag;
                    $probleminfo[$pid]->try_times = 0;
                    $probleminfo[$pid]->ac_times  = 0;
                }
            }
            $probleminfo_build = true;
        }

        $acset = [];
        foreach( $all as $row )
        {
            $uid=$row['uid'];
            $pid=$row['pid'];
            $verdict=$row['result'];
            $time=strtotime($row['timestamp'])-strtotime($this->starttime);
            if( $scoreboard[$uid][$pid]->is_ac != 0 )continue;

            $scoreboard[$uid][$pid]->try_times++;
            $probleminfo[$pid]->try_times++;
            if( $verdict == \SKYOJ\RESULTCODE::AC )
            {
                $scoreboard[$uid][$pid]->is_ac = 1;
                $scoreboard[$uid][$pid]->ac_time = (int)floor(($time + ($scoreboard[$uid][$pid]->try_times-1)*$this->penalty)/60);
                if( !isset($acset[$pid]) )
                {
                    $acset[$pid] = 1;
                    $scoreboard[$uid][$pid]->firstblood = 1;
                }
                $userinfo[$uid]->total_submit+=$scoreboard[$uid][$pid]->try_times;
                $userinfo[$uid]->ac_time+=$scoreboard[$uid][$pid]->ac_time;
                $userinfo[$uid]->ac++;
                $probleminfo[$pid]->ac_times++;
            }
        }

        usort($userinfo,[$this,'rank_cmp']);
        return  ['scoreboard'=>$scoreboard,'userinfo'=>$userinfo,'probleminfo'=>$probleminfo];
    }

    public function rank_cmp($a,$b)
    {
        $cmp = __NAMESPACE__."\\UserBlock::acm_cmp";
        return $cmp($a,$b);
    }

    public function get_scoreboard()
    {
        $start = $this->starttime;
        $end   = \SKYOJ\get_timestamp( max([ strtotime($start) , strtotime($this->endtime)-$this->freeze_sec ]) );
        return $this->get_scoreboard_by_timestamp($start,$end);
    }

    public function get_scoreboard_all()
    {
        $start = $this->starttime;
        $end   = $this->endtime;
        return $this->get_scoreboard_by_timestamp($start,$end);
    }
}