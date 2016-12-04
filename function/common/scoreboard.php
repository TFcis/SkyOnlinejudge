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
    private $sb_problems;
    private $sb_sb;

    protected function getTableName():string
    {
        static $t;
        if( isset($t) )return $t;
        return $t = \DB::tname('scoreboard');
    }

    protected function getIDName():string
    {
        return 'sb_id';
    }

    private $flag_modify_users = false;
    private $flag_modify_problems = false;
    protected function UpdateSQL_extend()
    {
        if( $this->sb_id() === null )
            throw new \Exception('SBID ERROR');
        //Update Users & Problems
        if( $this->flag_modify_users )
        {
            $tscoreboard_users = \DB::tname('scoreboard_users');
            if( \DB::queryEx("DELETE FROM `$tscoreboard_users` WHERE `sb_id`=?",$this->sb_id())===false )
                throw \DB::$last_exception;
            foreach( $this->sb_users as $uid )
            {
                if( \DB::queryEx("INSERT INTO `{$tscoreboard_users}`(`sb_id`, `uid`) VALUES (?,?)",$this->sb_id(),$uid) === false )
                {
                    throw \DB::$last_exception;
                }
            }
        }
        if( $this->flag_modify_problems )
        {
            $i = 0;
            $tscoreboard_problems = \DB::tname('scoreboard_problems');
            if( \DB::queryEx("DELETE FROM `$tscoreboard_problems` WHERE `sb_id`=?",$this->sb_id())===false )
                throw \DB::$last_exception;
            foreach( $this->sb_problems as $row )
            {
                if( \DB::queryEx("INSERT INTO `$tscoreboard_problems`(`sb_id`, `ord`, `problem`, `note`) VALUES (?,?,?,?)"
                    ,$this->sb_id(),$i++,$row['problem'],$row['note']) === false )
                {
                    throw \DB::$last_exception;
                }
            }
        }
        
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
            $this->sb_id = null;
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
        return true;
    }
    
    function GetStart():string
    {
        return $this->sb_data['start']??'';
    }

    function SetStart(string $start):bool
    {
        if( !check_totimestamp($start,$start) )
        {
            return false;
        }
        $this->UpdateSQLLazy('start',$start);
        return true;
    }

    function GetEnd():string
    {
        return $this->sb_data['end']??'';
    }

    function SetEnd(string $end):bool
    {
        if( !check_totimestamp($end,$end) )
        {
            return false;
        }
        $this->UpdateSQLLazy('end',$end);
        return true;
    }

    function GetAnnounce():string
    {
        return $this->sb_data['announce']??'';
    }

    function SetAnnounce(string $announce):bool
    {
        if( strlen($announce)>30000 )
        {
            return false;
        }
        $this->UpdateSQLLazy('announce',$announce);
        return true;
    }

    function GetState():int
    {
        return $this->sb_data['state']??0;
    }

    function owner():int
    {
        return $this->sb_data['owner'];
    }

    private function load_users():bool
    {
        if( isset($this->sb_users) )
        {
            return true;   
        }
        if( $this->sb_id() === false )
        {
            return false;
        }

        $tscoreboard_users = \DB::tname('scoreboard_users');
        $data = \DB::fetchAllEx("SELECT `uid` FROM {$tscoreboard_users} WHERE `sb_id`=? ORDER BY `uid`",$this->sb_id());
        if( $data === false )
        {
            return false;
        }
        $this->sb_users = [];
        foreach( $data as $row )
            $this->sb_users[] = $row['uid'];
        return true;
    }

    private function load_problems():bool
    {
        if( isset($this->sb_problems) )
        {
            return true;   
        }
        if( $this->sb_id() === false )
        {
            return false;
        }

        $tscoreboard_problems = \DB::tname('scoreboard_problems');
        $data = \DB::fetchAllEx("SELECT `problem`,`note` FROM {$tscoreboard_problems} WHERE `sb_id`=? ORDER BY `ord`",$this->sb_id());
        if( $data === false )
        {
            return false;
        }
        $this->sb_problems = [];
        foreach( $data as $row )
            $this->sb_problems[] = ['problem'=>$row['problem'],'note'=>$row['note']];
        return true;
    }

    function GetSortedUsers():array
    {
        static $res = null;
        if( isset($res) )
        {
            return $res;
        }
        $users = $this->GetUsers();
        $this->make_inline();
        $sb = $this->getScoreBoard();
        $total_score = [];
        foreach($sb as $uid => $pids)
        {
            $total_score[$uid] = 0;
            foreach($pids as $prob)
                $total_score[$uid]+= $prob[1];//score
        }
        usort($users,function($a,$b)use($total_score){
            return $total_score[$a]<=>$total_score[$b];
        });
        return $res = array_reverse($users);
    }

    function GetUsers():array
    {
        if( !$this->load_users() )
            throw new \Exception('ScoreBoard getUsers Failed!');
        return $this->sb_users;
    }

    function SetUsers($data):bool
    {
        if( is_string($data) )
        {
            $data = explode(',',$data);
        }
        if( !is_array($data) )
        {
            return false;
        }
        $users = [];
        foreach($data as $d)
        {
            if( empty($d) )
            {
                continue;
            }
            if( !check_tocint($d) || $d == 0 )
            {
                return false;
            }
            $users[]= (int)$d;
        }
        $this->sb_users = array_unique($users);
        $this->flag_modify_users = true;
        return true;
    }

    function GetProblems():array
    {
        if( !$this->load_problems() )
            throw new \Exception('ScoreBoard GetProblems Failed!');
        return $this->sb_problems;
    }

    function SetProblems($data):bool
    {
        if( is_string($data) )
        {
            $data = explode(',',$data);
        }
        if( !is_array($data) )
        {
            return false;
        }
        $problems = [];
        foreach( array_unique($data) as $row )
        {
            if( !empty($row) )
            {
                $problems[] = ['problem'=>$row,'note'=>''];
            }
        }
        $this->sb_problems = $problems;
        $this->flag_modify_problems = true;
        return true;
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

    /*
        Make Scoreboard Data
    */
    public function make_inline()
    {
        if( isset($this->sb_sb) )
            return ;
        $users = $this->GetUsers();
        $problems = $this->GetProblems();
        $skyoj_problems = [];
        $data = [];

        foreach( $users as $uid )
        {
            $data[$uid] = [];
            foreach( $problems as $problem )
            {
                $data[$uid][$problem['problem']] = [0,0];
                if( check_tocint($problem['problem']) )
                {
                    $skyoj_problems[] = $problem['problem'];
                }
            }
        }
        $this->sb_sb = $data;
        if( !empty($skyoj_problems) ){
            $data = $this->QuerySKYOJ($skyoj_problems);
        }
    }

    private function QuerySKYOJ($probs)
    {
        $qstr = rtrim(str_repeat('?,',count($probs)),',');
        $data = $probs;
        array_unshift($data,$this->sb_id());
        $data[] = $this->GetStart();
        $data[] = $this->GetEnd();
        //var_dump($probs,$data,$qstr);
        $tchallenge = \DB::tname('challenge');
        $tscoreboard_users = \DB::tname('scoreboard_users');
        $query= <<<TAG
SELECT
  uid,
  pid,
  score,
  MIN(result) AS mr
FROM
  `{$tchallenge}`
INNER JOIN
  (
  SELECT
    `uid` AS auid
  FROM
    `{$tscoreboard_users}`
  WHERE
    `sb_id` = ?
) a
ON
  uid = auid 
INNER JOIN
  (
  SELECT
    pid AS bpid,
    uid AS buid,
    MAX(score) ms
  FROM
    `{$tchallenge}`
  WHERE
    `pid` IN({$qstr}) AND timestamp BETWEEN ? AND ?
  GROUP BY
    pid,
    uid
) b
ON
  pid = b.bpid AND uid = b.buid AND `score` = b.ms
GROUP BY
  pid,
  uid
TAG;
        $res = \DB::fetchAll($query,$data);
        //var_dump($res);
        if( $res !== false )
        {
            foreach($res as $row)
            {
                $this->sb_sb[$row['uid']][$row['pid']] = [$row['mr'],$row['score']];
            }
        }
    }

    public function GetScoreBoard()
    {
        return $this->sb_sb;
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
