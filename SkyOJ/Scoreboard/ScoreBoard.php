<?php namespace SkyOJ\Scoreboard;

use \SkyOJ\Core\User\User;
use \SkyOJ\Core\Permission\ObjectLevel;

class ScoreBoard extends \SkyOJ\Core\CommonObject
{
    private $sb_data;
    private $sb_users;
    private $sb_problems;
    private $sb_sb;
    protected static $table = 'scoreboard';
    protected static $prime_key = 'sb_id';

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

    static $plugins = [];
    static function pluginInit()
    {
        global $_E;
        static $loaded = false;

        if( $loaded ) return;
        $loaded = true;

        //TODO Plugin Loder
        //$class_files = \SkyOJ\Helper\DirScanner::open($_E['ROOT'].'/SkyOJ/Scoreboard/Plugin/*.php');
        $class_files = OJCaptureEnum::getConstants();
        unset( $class_files[OJCaptureEnum::str(OJCaptureEnum::None)] );
        foreach($class_files as $cl)
        {
            $base = OJCaptureEnum::str($cl);
            $classname = '\\SkyOJ\\Scoreboard\\Plugin\\'.$base;
            self::$plugins[$base] = new $classname;
        }
    }

    private function loadcache()
    {
        global $SkyOJ; 
        $this->sb_sb = $SkyOJ->cache_pool->get("Scoreboard_$this->sb_id",[]);
    }

    private function setcache()
    {
        global $SkyOJ; 
        $SkyOJ->cache_pool->set("Scoreboard_$this->sb_id",$this->sb_sb,time()+8640000);
    }

    function __construct()
    {
        self::pluginInit();
    }

    function checkSet_title(string $title):bool
    {
        if( strlen($title)>100 )
        {
            return false;
        }
        return true;
    }

    function checkSet_start(string $start):bool
    {
        if( !\SKYOJ\check_totimestamp($start,$start) )
        {
            return false;
        }
        return true;
    }

    function checkSet_end(string $end):bool
    {
        if( !\SKYOJ\check_totimestamp($end,$end) )
        {
            return false;
        }
        return true;
    }

    function checkSet_allow_join(string $allow_join):bool
    {
		if ( !ScoreBoardAllowJoinEnum::isValidValue((int)$allow_join) )
			return false;
        return true;
    }

    function checkSet_announce(string $announce):bool
    {
        if( strlen($announce)>30000 )
        {
            return false;
        }
        return true;
    }

    private function load_users():bool
    {
        if( isset($this->sb_users) )
        {
            return true;   
        }
        if( $this->sb_id === false )
        {
            return false;
        }

        $tscoreboard_users = \DB::tname('scoreboard_users');
        $data = \DB::fetchAllEx("SELECT `uid` FROM {$tscoreboard_users} WHERE `sb_id`=? ORDER BY `uid`",$this->sb_id);
        if( $data === false )
        {
            return false;
        }
        $this->sb_users = [];
        foreach( $data as $row )
            $this->sb_users[] = $row['uid'];
        return true;
    }

    private $prob_match = [];
    private function load_problems():bool
    {
        if( isset($this->sb_problems) )
        {
            return true;   
        }
        if( $this->sb_id === false )
        {
            return false;
        }

        $tscoreboard_problems = \DB::tname('scoreboard_problems');
        $data = \DB::fetchAllEx("SELECT `problem`,`note` FROM {$tscoreboard_problems} WHERE `sb_id`=? ORDER BY `ord`",$this->sb_id);
        if( $data === false )
        {
            return false;
        }

        $this->sb_problems = [];
        foreach( $data as $row )
        {
            foreach( self::$plugins as $name => $plugin )
            {
                if( $plugin->is_match($row['problem']) )
                {
                    $this->prob_match[$row['problem']] = $name;
                }
            }
            if(!array_key_exists($row['problem'],$this->prob_match))
                continue;
            $this->sb_problems[] = ['problem'=>$row['problem'],'note'=>$row['note']];
        }
        return true;
    }

    function problem_title(string $pname):string
    {
        if( isset($this->prob_match[$pname]) )
            return self::$plugins[ $this->prob_match[$pname] ]::get_title($pname);
        return '[NoPlugin]';
    }

    function problem_link(string $pname):string
    {
        if( isset($this->prob_match[$pname]) )
            return self::$plugins[ $this->prob_match[$pname] ]::problink($pname);
        return '';
    }
	
	function isAllowJoin(int $uid):bool
	{
		if ( $this->allow_join == ScoreBoardAllowJoinEnum::NotAllowed )
			return false;
		$users = $this->GetUsers();
		return !in_array($uid, $users);
	}

    function GetSortedUsers():array
    {
        static $res = null;
        if( isset($res) )
        {
            return $res;
        }
        $users = $this->GetUsers();
        //$this->make_inline();
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
	
	function SetAllowJoin(int $value):bool
	{
		if ( !ScoreBoardAllowJoinEnum::isValidValue($value) )
			return false;
		return $this->allow_join = $value;
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
            if( !\SKYOJ\check_tocint($d) || $d == 0 )
            {
                return false;
            }
            $users[]= (int)$d;
        }
        $this->sb_users = array_unique($users);
        $tscoreboard_users = \DB::tname('scoreboard_users');
        if( \DB::queryEx("DELETE FROM `{$tscoreboard_users}` WHERE `sb_id`=?",$this->sb_id)===false )
            throw \DB::$last_exception;
        foreach( $this->sb_users as $uid )
        {
            if( \DB::queryEx("INSERT INTO `{$tscoreboard_users}` (`sb_id`, `uid`) VALUES (?,?)",$this->sb_id,$uid) === false )
            {
                throw \DB::$last_exception;
            }
        }
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
        $i = 0;
        $tscoreboard_problems = \DB::tname('scoreboard_problems');
        if( \DB::queryEx("DELETE FROM `$tscoreboard_problems` WHERE `sb_id`=?",$this->sb_id)===false )
            throw \DB::$last_exception;
        foreach( $this->sb_problems as $row )
        {
            if( \DB::queryEx("INSERT INTO `$tscoreboard_problems`(`sb_id`, `ord`, `problem`, `note`) VALUES (?,?,?,?)"
                ,$this->sb_id,$i++,$row['problem'],$row['note']) === false )
            {
                throw \DB::$last_exception;
            }
        }
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

    public function rebuild($uids=null, $pros=null)
    {
        global $SkyOJ,$_E;
        try
        {
            set_time_limit(0);
            ignore_user_abort(true);
            $lockfile = fopen($_E['DATADIR']."cache/Scoreboard_working","w");
            $waittime = 0;
            while( !flock($lockfile,LOCK_EX) )
            {
                if($waittime>300)
                {
                    throw new \Exception('WORKING, try later');
                }
                $wait = rand(1,10);
                sleep($wait);
                $waittime += $wait;
            }

            $users = $this->GetUsers();
            $problems = $this->GetProblems();
            $data = [];
            $users_pool = [];

            if($uids==null)
            {
                $users_pool = $users;
            }
            else
            {
                foreach($users as $user)
                {
                    if(in_array($user,$uids) && $this->rebuildUserable($SkyOJ->User,$user))
                        $users_pool[] = $user;
                }
            }
            
            $problems_pool = [];
            foreach( $problems as $prob )
            {
                $pname = $prob['problem'];
                if($pros!==null)
                {
                    if(!in_array($pname,$pros))
                    {
                        continue;
                    }
                }

                if( array_key_exists($pname,$this->prob_match) )
                {
                    $class = $this->prob_match[$pname];
                    if( !isset($problems_pool[$class]) )
                        $problems_pool[$class] = [];
                    $problems_pool[$this->prob_match[$pname]][] = $pname;
                }
            }
            //echo json_encode($users_pool)."\n";
            //echo json_encode($problems_pool)."\n";

            foreach( $problems_pool as $class => $probs )
            {
                self::$plugins[$class]->rebuild($users_pool,$probs);
            }
            $this->make_inline();

            fclose($lockfile);
        }
        catch(\Exception $e)
        {
            return [false,$e->getMessage()];
        }
        
        return [true,""];
    }

    public function rebuildUserable(User $user, $uid):bool
    {
        return $user->testStisfyPermission($uid, ObjectLevel::ADMIN);
    }

    public function rebuildAllable(User $user):bool
    {
        return $user->testStisfyPermission($this->owner, ObjectLevel::ADMIN);
    }

    /*
        Make Scoreboard Data
    */
    public function make_inline()
    {
        $users = $this->GetUsers();
        $problems = $this->GetProblems();
        $skyoj_problems = [];
        $data = [];

        // Prepare
        $problems_pool = [];
        foreach( $problems as $prob )
        {
            $pname = $prob['problem'];
            if( array_key_exists($pname,$this->prob_match) )
            {
                $class = $this->prob_match[$pname];
                if( !isset($problems_pool[$class]) )
                    $problems_pool[$class] = [];
                $problems_pool[$this->prob_match[$pname]][] = $pname;
            }
        }

        foreach( $problems_pool as $class => $probs )
        {
            self::$plugins[$class]->prepare($users,$probs);
        }

        foreach( $users as $uid )
        {
            $data[$uid] = [];
            foreach( $problems as $problem )
            {
                $data[$uid][$problem['problem']] = [0,0];
                $data[$uid][$problem['problem']]['challink'] = '';
                if( \SKYOJ\check_tocint($problem['problem']) )
                {
                    $skyoj_problems[] = $problem['problem'];
                }
                else
                {
                    $pname = $problem['problem'];
                    $class = $this->prob_match[$pname];
                    $data[$uid][$pname] = self::$plugins[$class]->query($uid,$pname,strtotime($this->start),strtotime($this->end));
                    $data[$uid][$problem['problem']]['challink'] = self::$plugins[$class]->challink($uid,$pname);
                }
            }
        }
        $this->sb_sb = $data;
        $this->setcache();
        if( !empty($skyoj_problems) ){
            $this->QuerySKYOJ($skyoj_problems);
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
        if(!isset($this->sb_sb))
            $this->loadcache();
        return $this->sb_sb;
    }

    static function CreateNew(string $title,int $type):int
    {
        global $_G;
        $tstatsboard = \DB::tname('scoreboard');
		//error_log( (string)\DB::$pdo->errorInfo() );
        $res = \DB::queryEx("INSERT INTO `{$tstatsboard}`
            (`sb_id`, `title`, `owner`, `type`, `type_note`, `timestamp`, `allow_join`) 
            VALUES (NULL,?,?,?,NULL,NULL,?)",
                       $title,$_G['uid'],$type,ScoreBoardAllowJoinEnum::Allowed);
        if( $res===false )
            throw new \Exception('SQL Error!');
        $sb_id = \DB::lastInsertId('sb_id');
        return $sb_id;
    }
}
