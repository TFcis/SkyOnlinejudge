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

    static function getallowlist():array
    {
        static $c;
        if( !isset($c) )
        {
            $data = self::getConstants();
            $c = [];
            foreach($data as $val)
            {
                if( self::allow($val) )
                {
                    $c[] = $val;
                }
            }
        }
        return $c;
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
    static $column=['cont_id','uid','team_id','state','timestamp','note'];
    public $cont_id;
    public $uid;
    public $team_id;
    public $state;
    public $timestamp;
    public $note;
}

class ScoreBlock
{
    public $try_times;
    public $ac_time;
    public $is_ac;
    public $firstblood;
    public $score;
}

class UserBlock
{
    public $uid;
    public $total_submit;
    public $ac;
    public $ac_time;
    public $score;
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
    private $manger;
    private $problems_update;
    private $flag_modify_problems;

    const TITLE_LENTH_MAX = 200;
    protected function UpdateSQL_extend()
    {
        if( $this->cont_id() === null )
            throw new \Exception('CONT_ID ERROR');
        if( $this->flag_modify_problems )
        {
            $tcontest_problems = \DB::tname('contest_problem');
            if( \DB::queryEx("DELETE FROM `$tcontest_problems` WHERE `cont_id`=?",$this->cont_id())===false )
                throw \DB::$last_exception;
            foreach( $this->problems_update as $row )
            {
                if( \DB::queryEx("INSERT INTO `$tcontest_problems`(`cont_id`, `pid`, `ptag`, `state`, `priority`) VALUES (?,?,?,?,?)"
                    ,$this->cont_id(),$row[1],$row[0],$row[2],$row[3]) === false )
                {
                    throw \DB::$last_exception;
                }
            }
        }
    }

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

    function GetProblems():array
    {
        $data = $this->get_all_problems_info();
        $data_output = [];
        foreach($data as $r){
            if(!empty($r)){
                $ptag = $r->ptag;
                $pid = $r->pid;
                $pstate = $r->state;
                $priority = $r->priority;
                $output = $ptag.':'.$pid.':'.$pstate.':'.$priority;
                $data_output[] = $output;
            }
        }
        return $data_output;
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
            $class = empty($data['class']) ? "class_ACM_ICPC" : $data['class'];
            $class = \Plugin::loadClassFile('contest',$class);
            $this->manger = new $class;
        }
        
    }

    function cont_id():int
    {
        return $this->cont_id;
    }

    function scoreboard_template($resolver=false):array
    {
        return $this->manger->scoreboard_template($resolver);
    }
    
    function resolver_template():array
    {
        return $this->manger->resolver_template();
    }

    protected function set_title(string $title):bool
    {
        if( strlen($title) > self::TITLE_LENTH_MAX )
            throw new CommonObjectError("title length should less than:".self::TITLE_LENTH_MAX,SKY_ERROR::UNKNOWN_ERROR);

        $this->UpdateSQLLazy('title',$title);
        return true;
    }

    public function set_starttime(string $start):bool
    {
        if( !check_totimestamp($start,$start) )
            throw new CommonObjectError("starttime format error",SKY_ERROR::UNKNOWN_ERROR);

        $this->UpdateSQLLazy('starttime',$start);
        return true;
    }

    public function set_endtime(string $end):bool
    {
        if( !check_totimestamp($end,$end) )
            throw new CommonObjectError("endtime format error",SKY_ERROR::UNKNOWN_ERROR);

        $this->UpdateSQLLazy('endtime',$end);
        return true;
    }

    public function set_register_type(int $reg_type):bool
    {
        if( !ContestUserRegisterStateEnum::isValidValue($reg_type) )
            throw new CommonObjectError("register type",SKY_ERROR::NO_SUCH_ENUM_VALUE);

        $this->UpdateSQLLazy('register_type',$reg_type);
        return true;
    }

    public function set_register_beginsec(int $begin):bool
    {
        $this->UpdateSQLLazy('register_beginsec',$begin);
        return true;
    }

    public function set_register_delaysec(int $delay):bool
    {
        $this->UpdateSQLLazy('register_delaysec',$delay);
        return true;
    }

    public function set_penalty(int $penalty):bool
    {
        $this->UpdateSQLLazy('penalty',$penalty);
        return true;
    }

    public function set_freeze_sec(int $freezesec):bool
    {
        $this->UpdateSQLLazy('freeze_sec',$freezesec);
        return true;
    }

    public function set_class(string $class):bool
    {
        $var = \Plugin::checkInstall($class);
        if( !$var || !$var[$class] )
            throw new CommonObjectError("set_class error",SKY_ERROR::NO_SUCH_DATA);
        $this->UpdateSQLLazy('class',$class);
        return true;
    }

    //TODO: Rewrite this
    public function set_problems(string $problems):bool
    {
        $data = explode(',',$problems);
        $this->problems_update = [];
        foreach($data as $row){
            if(!empty($row)){
                $p = explode(':',$row);
                $this->problems_update[] = $p;
            }
        }
        $this->flag_modify_problems = true;
        return true;
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

    function get_user_info(int $uid):ContestUserInfo
    {
        $table = \DB::tname('contest_user');
        $user = \DB::fetchEx("SELECT * FROM {$table} WHERE `cont_id`=? AND `uid`=?",$this->cont_id(),$uid);
        if( $user === false )
        {
            throw new CommonObjectError('no such data',SKY_ERROR::NO_SUCH_DATA);
        }
        $tmp = new ContestUserInfo();
        foreach( ContestUserInfo::$column as $c )
            $tmp->$c = $user[$c];
        return $tmp;
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

    function isfreeze():bool
    {
        return strtotime($this->endtime)-$this->freeze_sec < strtotime($this->now_time);
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
            if(!ContestProblemStateEnum::allow($row['state'])){
                continue;
            }
            $tmp = new ContestProblemInfo();
            foreach( ContestProblemInfo::$column as $c )
            {
                $tmp->$c = $row[$c];
            }
            $data[]=$tmp;
        }
        return $data;
    }

    /**
     *  get_user_problems_info
     *  if manger have function get_user_problems_info ,this will return manger's value
     *  else return all pid get from get_all_problems_info();
     */
    function get_user_problems_info(int $uid):array
    {
        return $this->manger->get_user_problems_info($this,$uid);
    }

    //ScoreBoard
    public function get_chal_data_by_timestamp($start,$end):array
    {
        $tname = \DB::tname('challenge');
        $tuid  = \DB::tname('contest_user');
        $tpid  = \DB::tname('contest_problem');
        $allow_type = ContestTeamStateEnum::getallowlist();
        $u = implode(",",$allow_type);
        $all = \DB::fetchAllEx("SELECT `pid`,`uid`,`result`,`score`,`timestamp` FROM $tname 
            WHERE  `timestamp` BETWEEN ? AND ? 
                AND `uid` IN (SELECT `uid` FROM $tuid WHERE `cont_id`=? AND `state` IN ($u) ) 
                AND `pid` IN (SELECT `pid` FROM $tpid WHERE `cont_id`=?) 
            ORDER BY `cid` ASC",
            $start,$end,$this->cont_id(),$this->cont_id()
        );

        return $all;
    }

    public function get_scoreboard_by_timestamp($start,$end)
    {
        if( method_exists($this->manger,'get_scoreboard_by_timestamp') )
        {
            return $this->manger->get_scoreboard_by_timestamp($this,$start,$end);
        }
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
            if( !ContestTeamStateEnum::allow($user->state) )
            {
                continue;
            }
            $userinfo[$uid] = new UserBlock();
            $userinfo[$uid]->uid=$uid;
            $userinfo[$uid]->total_submit=0;
            $userinfo[$uid]->ac=0;
            $userinfo[$uid]->ac_time=0;
            $userinfo[$uid]->score=0;

            $scoreboard[$uid]=[];
            foreach($pids as $row)
            {
                $pid=$row->pid;
                $ptag=$row->ptag;
                $scoreboard[$uid][$pid]=new ScoreBlock();
                $scoreboard[$uid][$pid]->try_times = 0;
                $scoreboard[$uid][$pid]->is_ac     = 0;
                $scoreboard[$uid][$pid]->ac_time   = 0;
                $scoreboard[$uid][$pid]->firstblood= 0;
                $scoreboard[$uid][$pid]->score     = 0;
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
            $ptag='';
            foreach($pids as $p){
                if($p->pid==$row['pid']){
                    $ptag=$p->ptag;
                    break;
                }
            }
            $verdict=$row['result'];
            $time=strtotime($row['timestamp'])-strtotime($this->starttime);
            if( $scoreboard[$uid][$pid]->is_ac != 0 )continue;

            $scoreboard[$uid][$pid]->try_times++;
            $probleminfo[$pid]->try_times++;
            if( $row['score'] > $scoreboard[$uid][$pid]->score )
            {
                $delta = $row['score'] - $scoreboard[$uid][$pid]->score; 
                $scoreboard[$uid][$pid]->score = $row['score'];
                $userinfo[$uid]->score += $delta;
            }
            if( $verdict == \SKYOJ\RESULTCODE::AC )
            {
                $scoreboard[$uid][$pid]->is_ac = 1;
                $scoreboard[$uid][$pid]->ac_time = (int)floor($time/60); 
                if( !isset($acset[$pid]) )
                {
                    $acset[$pid] = 1;
                    $scoreboard[$uid][$pid]->firstblood = 1;
                }
                $userinfo[$uid]->total_submit+=$scoreboard[$uid][$pid]->try_times;
                $userinfo[$uid]->ac_time+=(int)floor(($time + ($scoreboard[$uid][$pid]->try_times-1)*$this->penalty)/60);
                $userinfo[$uid]->ac++;
                $probleminfo[$pid]->ac_times++;
            }
        }
        
        usort($userinfo,[$this,'rank_cmp']);
        return  ['scoreboard'=>$scoreboard,'userinfo'=>$userinfo,'probleminfo'=>$probleminfo];
    }

    public function rank_cmp($a,$b)
    {
        return $this->manger->compare($a,$b);
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
    
    public function get_resolver()
    {
        $start = $this->starttime;
        $end   = \SKYOJ\get_timestamp( max([ strtotime($start) , strtotime($this->endtime)-$this->freeze_sec ]) );
        $scdata = $this->get_scoreboard_by_timestamp($start,$end);
        return $this->to_resolver_json($scdata);
    }

    public function get_resolver_all()
    {
        $start = $this->starttime;
        $end   = $this->endtime;
        $scdata = $this->get_scoreboard_by_timestamp($start,$end);
        return $this->to_resolver_json($scdata);
    }
    
    public function to_resolver_json($scordboard_data)
    {
        if( method_exists($this->manger,'to_resolver_json') )
        {
            return $this->manger->to_resolver_json($this,$scordboard_data);
        }
        
        //solved attempted
        $json = [];
        $json["solved"] = [];
        $json["attempted"] = [];
        foreach($scordboard_data['probleminfo'] as $prob)
        {
            $json["solved"][$prob->ptag] = $prob->ac_times;
            $json["attempted"][$prob->ptag] = $prob->try_times;
        }
        $rank = 1;
        $last = null;
        $json["scoreboard"] = [];
        foreach($scordboard_data['userinfo'] as $user)
        {
            if( isset($last)&&$this->rank_cmp($last,$user)!=0 ){
                $rank++;
            }
            $last = $user;
            $d = [];
            $d['id'] = (int)$user->uid;
            $d['rank'] = $rank;
            $d['solved'] = (int)$user->ac;
            $d['points'] = (int)$user->ac_time;

            $nickname=\SKYOJ\nickname($user->uid);
            $d['name'] = $nickname[$user->uid];
            $d['group'] = '';

            foreach($scordboard_data['probleminfo'] as $prob)
            {
                $sb=$scordboard_data['scoreboard'][$user->uid][$prob->pid];
                $d[$prob->ptag] = [];
                $d[$prob->ptag]['a'] = $sb->try_times;
                if( $sb->is_ac )
                {
                    $d[$prob->ptag]['t'] = $sb->ac_time;
                }
                if( $sb->firstblood )$d[$prob->ptag]['s'] = "first";
                else if( $sb->is_ac )$d[$prob->ptag]['s'] = "solved";
                else if( $sb->try_times ) $d[$prob->ptag]['s'] = "tried";
                else $d[$prob->ptag]['s'] = "nottried";
            }

            $json["scoreboard"][] = $d;
            
        }
        return json_encode($json);
    }
}