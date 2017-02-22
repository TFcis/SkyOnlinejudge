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

class Challenge extends \SKYOJ\CommonObject
{
    private $cid;
    private $uid;
    private $pid;
    private $id;

    protected function getTableName():string
    {
        return \DB::tname('challenge');
    }

    protected function getIDName():string
    {
        return 'cid';
    }

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

    public function __construct(int $cid)
    {
        $tchallenge = $this->getTableName();
        $this->sqldata = \DB::fetchEx("SELECT `cid`, `pid`, `uid`, `result`, `runtime`, `score`, `timestamp`,`comment` FROM `{$tchallenge}` WHERE `cid` = ?",$cid);
        if( $this->sqldata == false ){
            $this->cid = null;
            return ;
        }
        $this->cid = $cid;
    }

    private function get_code_lazy()
    {
        static $code = [];
        if( isset($code[$this->cid]) ){
            return $code[$this->cid];
        }
        $tchallenge = $this->getTableName();
        $d = \DB::fetchEx("SELECT `code` FROM `{$tchallenge}` WHERE `cid` = ?",$this->cid);
        return $code[$this->cid] = ($d===false)?'':$d['code'];
    }
    
    public function cid():int
    {
        return $this->cid;
    }

    public function uid():int
    {
        return $this->sqldata['uid'];
    }

    public function pid():int
    {
        return $this->sqldata['pid'];
    }

    public function code():string
    {
        return $this->get_code_lazy();
    }

    public function compiler():int
    {
        return $this->sqldata['compiler']??'';
    }

    public function result():int
    {
        return $this->sqldata['result'];
    }
    public function runtime():int
    {
        return $this->sqldata['time'];
    }

    public function score():int
    {
        return $this->sqldata['score'];
    }

    public function timestamp():string
    {
        return $this->sqldata['timestamp'];
    }

    public function sqldata()
    {
        return $this->sqldata;
    }

    public function set_comment(string $comment)
    {
        $this->UpdateSQLLazy('comment',$comment);
    }

    public function set_result(int $code):bool
    {
        $this->UpdateSQLLazy('result',$code);
        return $this->UpdateSQL();
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

        $this->UpdateSQLLazy('package',$res);
        $this->UpdateSQLLazy('result',$problem_stat);
        $this->UpdateSQLLazy('score',$total_score);
        $this->UpdateSQLLazy('runtime',$total_time);
        if( !$this->UpdateSQL() )
        {
            \Log::msg(\Level::Error,"(Challenge)UPDATE challenge SQL Error!");
            return false;
        }
        return true;

        //Update Score
    }
}
