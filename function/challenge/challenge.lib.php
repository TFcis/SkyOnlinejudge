<?php namespace SKYOJ\Challenge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'function/common/problem.php';
class ChallengeTask
{
    public $taskid; //subid
    public $runtime;//ms
    public $mem;    //in KB
    public $state;  //AC WA..
    public $score;  //sub score
    public $msg;    //judge message
}

class Challenge
{
    private $cid;
    private $uid;
    private $pid;
    private $id;
    private $sql_data;

    private $light = false;


    public static function create(int $uid,int $pid,string $code,string $compiler)
    {
        global $_E,$_G;
        $tchallenge = \DB::tname('challenge');
        if(!\DB::queryEx("INSERT INTO `{$tchallenge}`(`cid`, `uid`, `pid`, `code`, `compiler`, `result`, `runtime`, `score`, `timestamp`)
                         VALUES (NULL,?,?,?,?,?,0,0,NULL)",$uid,$pid,$code,$compiler,\SKYOJ\RESULTCODE::WAIT))
        {
            return null;
        }
        return \DB::lastInsertId('cid');
    }

    public function __construct(int $cid,$light = false)
    {
        $tchallenge = \DB::tname('challenge');
        if( $this->light = $light ){
            $this->sql_data = \DB::fetchEx("SELECT `cid`, `pid`, `uid`, `result`, `runtime`, `score`, `timestamp` FROM `{$tchallenge}` WHERE `cid` = ?",$cid);
        }else{
            $this->sql_data = \DB::fetchEx("SELECT * FROM `{$tchallenge}` WHERE `cid` = ?",$cid);
        }

        if( $this->sql_data == false ){
            $this->cid = null;
            return ;
        }
        $this->cid = $cid;
    }
    
    public function cid():int
    {
        return $this->cid;
    }

    public function uid():int
    {
        return $this->sql_data['uid'];
    }

    public function pid():int
    {
        return $this->sql_data['pid'];
    }

    public function code():string
    {
        return $this->sql_data['code']??'';
    }

    public function compiler():int
    {
        return $this->sql_data['compiler']??'';
    }

    public function result():int
    {
        return $this->sql_data['result'];
    }
    public function runtime():int
    {
        return $this->sql_data['time'];
    }

    public function score():int
    {
        return $this->sql_data['score'];
    }

    public function timestamp():string
    {
        return $this->sql_data['timestamp'];
    }

    public function sql_data()
    {
        return $this->sql_data;
    }

    public function set_result(int $code)
    {
        $tchallenge = \DB::tname('challenge');
        \DB::queryEx("UPDATE `{$tchallenge}` SET `result` = ? WHERE `cid` = ?",$code,$this->cid());
    }

    public function run_judge():bool //No Reply
    {
        //Load problem
        $problem = new \SKYOJ\Problem($this->pid());
        if( $problem->pid()===null )
        {
            \Log::msg(\Level::Error,"(Challenge)run_judge load {$this->pid()} failed!");
            return false;
        }

        if( \Plugin::loadClassFileInstalled('judge',$problem->GetJudge())===false )
        {
            \Log::msg(\Level::Error,"(Challenge)run_judge load judge {$problem->GetJudge()} failed!");
            return false;
        }
        $this->set_result(\SKYOJ\RESULTCODE::JUDGING);

        $judge = $problem->GetJudge();
        $judge = new $judge;
        
        $res = $judge->judge($this);
       
        if( $res === false )
        {
            \Log::msg(\Level::Error,"(Challenge)run_judge judge error!");
            $this->set_result(\SKYOJ\RESULTCODE::JE);
            return false;
        }

        $problem_stat = 0;
        $total_score  = 0;
        $total_time   = 0;
        foreach($res as $row)
        {
            $problem_stat = max([$problem_stat,$row->state]);
            $total_score += $row->score;
            $total_time  += $row->runtime;
        }

        $res = json_encode($res);
        $tchallenge = \DB::tname('challenge');
        if( !\DB::queryEx("UPDATE `{$tchallenge}` SET `package`=?,`result`=?,`score` =?,`runtime` =?
                           WHERE `cid` = ?",$res,$problem_stat,$total_score,$total_time,$this->cid()) )
        {
            \Log::msg(\Level::Error,"(Challenge)UPDATE challenge SQL Error!");
            return false;
        }
        return true;

        //Update Score
    }
}
