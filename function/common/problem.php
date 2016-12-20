<?php namespace SKYOJ;
/*
 * problem
 * 2016 Sky Online Judge Project
 * By LFsWang
 *
 */
/*
Storge format
SQL : id,phash,status
data/problem/prased.html
*/
//require_once $_E['ROOT'].'/function/externals/Parsedown.php';

class ProblemDescriptionEnum extends BasicEnum
{
    const PDF       = '0';
    const MarkDown  = '1';
    const HTML      = '2';
}

class ProblemJudgeTypeEnum extends BasicEnum
{
    const Hidden    = 0; //< Not support
    const Normal    = 1; //< STD IO
    const FileIO    = 2; //< File IO
}

class ProblemContentAccessEnum extends BasicEnum
{
    const Hidden    = 0; //< Only Access >= can see problem
    const Open      = 1; //< All users
    const Contest   = 2; //< Only user in contest or admin access
}

class ProblemSubmitAccessEnum extends BasicEnum
{
    const Closed    = 0; //< All users cannot submit (it will not effct submited challenge)
    const Test      = 1; //< Only Access >= can submit problem
    const Open      = 2; //< All users can submit it
    const Contest   = 3; //< Only user in contest or admin access
}

class Problem
{
    private $SQLData = [];
    private $config = [];
    private $pid = null;
    private $row_changed = false;
    
    const TITLE_LENTH_MAX = 200;
    const RENDERED_FILE = 'rendered.html';
    const ROW_FILE = 'row.txt';
    const CONFIG_FILE = 'conf.json';

    static private function GetDefaultJsonData()
    {
        return [
            'content' => [
                'type' => ProblemDescriptionEnum::MarkDown,
            ]
        ];
    }

    static public function CheckPIDFormat($pid):bool
    {
        if( !is_string($pid) && !is_int($pid) )
            return false;
        return preg_match('/[1-9][0-9]*/', $pid);
    }

    //TODO: Add Site Key
    static public function GetProblemFolderHash(int $pid):string
    {
        return $pid;
    }

    static public function GetProblemBaseFolder(int $pid):string
    {
        global $_E;
        $path = $_E['DATADIR'].'problem/';
        $baseFolder = self::GetProblemFolderHash($pid);
        return $path.$baseFolder.'/';
    }

    static public function GetHttpFolder(int $pid):string
    {
        return self::GetProblemBaseFolder($pid).'http/';
    }

    static public function GetTestdataFolder(int $pid):string
    {
        return self::GetProblemBaseFolder($pid).'testdata/';
    }

    static public function GetMakeFolder(int $pid):string
    {
        return self::GetProblemBaseFolder($pid).'make/';
    }

    static public function CreateDefault(int $pid):bool
    {
        if( !\SKYOJ\CreateFolder(self::GetProblemBaseFolder($pid)) || 
            !\SKYOJ\CreateFolder(self::GetHttpFolder($pid)) || 
            !\SKYOJ\CreateFolder(self::GetTestdataFolder($pid)) ||
            !\SKYOJ\CreateFolder(self::GetMakeFolder($pid))
        ){
            return false;
        }
        file_put_contents( self::GetProblemBaseFolder($pid).self::CONFIG_FILE , json_encode(self::GetDefaultJsonData()) );
        return true;
    }

    public function __construct($pid)
    {
        if( !self::CheckPIDFormat($pid) )
            throw new \Exception('No Such PID'); 

        $tproblem = \DB::tname('problem');
        $data = \DB::fetchEx("SELECT * FROM `{$tproblem}` WHERE `pid`=?", $pid);
        if ( !$data ) 
            throw new \Exception('SQL Error');
        $this->SQLData = $data;

        $config = file_get_contents(self::GetProblemBaseFolder($pid).self::CONFIG_FILE);
        if( $config === false )
            throw new \Exception('Config Error');

        $this->config = json_decode($config,true);
        if( $this->config === NULL )
            throw new \Exception('Bad Config!');
    }

    public static function get_title(int $pid)
    {
        static $t = [];
        $tproblem = \DB::tname('problem');
        if( isset($t[$pid]) )
            return $t[$pid];
        $data = \DB::fetchEx("SELECT `title` FROM `{$tproblem}` WHERE `pid`=?", $pid);
        if( $data !== false )
            return $t[$pid] = $data['title'];
        return '(null)';
    }

    public function __destruct()
    {
        $this->Update();
    }

    private function WriteConfigToFile():bool
    {
        return file_put_contents(
                    self::GetProblemBaseFolder($this->pid()).self::CONFIG_FILE,
                    json_encode($this->config)
                )!==false;
    }

    public function pid():int
    {
        return $this->SQLData['pid']??null;
    }

    public function owner():int
    {
        return $this->SQLData['owner']??null;
    }

    /**
     * this function will not check $col
     */
    private function UpdateSQLLazy(string $col = null,$val = null)
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
        $tproblem = \DB::tname('problem');
        $data = $this->UpdateSQLLazy();
        //TODO : Need report sql status
        foreach( $data as $d )
            \DB::queryEx("UPDATE `{$tproblem}` SET `{$d[0]}`= ? WHERE `pid`=?",$d[1],$this->pid());
        return true;
    }

    public function GetContentAccess():int
    {
        return $this->SQLData['content_access']??null;
    }

    public function SetContentAccess($val):bool
    {
        if( ProblemContentAccessEnum::isValidValue($val) )
        {
            return false;
        }
        $this->UpdateSQLLazy('content_access',$val);
        return true;
    }

    public static function uid_contest_playing_problem_set(int $uid)
    {
        static $cache = [];
        if( isset($cache[$uid]) )return $cache[$uid];
        $tc  = \DB::tname("contest");
        $tcp = \DB::tname("contest_problem");
        $tcu = \DB::tname("contest_user");
        $now = \SKYOJ\get_timestamp(time());
        $res = \DB::fetchAllEx("
SELECT DISTINCT `pid` FROM `{$tcp}`
	INNER JOIN `tojtest_contest`
    	ON `{$tcp}`.`cont_id`=`{$tc}`.`cont_id`
    WHERE `{$tcp}`.`cont_id` 
    	IN (SELECT `cont_id` FROM `{$tcu}` WHERE `uid` = ?)
    AND `starttime`<= ? 
    AND ? <= `endtime`
        ",$uid,$now,$now);

        \log::msg(\Level::Debug,'',$res);
        if( $res === false )
            return [];
        $cache[$uid]=[];
        foreach($res as $row)
            $cache[$uid][] = (int)$row['pid'];
        return $cache[$uid];
    }

    public static function hasContentAccess_s(int $uid,int $owner,int $acccode,int $pid):bool
    {
        switch($acccode)
        {
            case ProblemContentAccessEnum::Hidden: return $owner===$uid || \userControl::isAdmin($uid);
            case ProblemContentAccessEnum::Open:   return true;
            case ProblemContentAccessEnum::Contest:
                if(  $owner===$uid || \userControl::isAdmin($uid) )return true;
                return in_array($pid,self::uid_contest_playing_problem_set($uid));
            default: \SKYOJ\NeverReach();
        }
    }

    public function hasContentAccess(int $uid):bool
    {
        return self::hasContentAccess_s($uid,$this->owner(),$this->GetContentAccess(),$this->pid());
    }

    public function GetSubmitAccess():int
    {
        return $this->SQLData['submit_access']??null;
    }

    public function SetSubmitAccess($val):bool
    {
        if( ProblemSubmitAccessEnum::isValidValue($val) )
        {
            return false;
        }
        $this->UpdateSQLLazy('submit_access',$val);
        return true;
    }

    public static function hasSubmitAccess_s(int $uid,int $owner,int $acccode,int $pid):bool
    {
        switch($acccode)
        {
            case ProblemSubmitAccessEnum::Closed: return false;
            case ProblemSubmitAccessEnum::Test:   return $owner===$uid || \userControl::isAdmin($uid);
            case ProblemSubmitAccessEnum::Open:   return $uid!=0;
            case ProblemSubmitAccessEnum::Contest:
                if( $owner===$uid || \userControl::isAdmin($uid) )return true;
                return in_array($pid,self::uid_contest_playing_problem_set($uid));
            default: \SKYOJ\NeverReach();
        }
    }

    public function hasSubmitAccess(int $uid):bool
    {
        return self::hasSubmitAccess_s($uid,$this->owner(),$this->GetSubmitAccess(),$this->pid());
    }

    public function GetJudge():string
    {
        return $this->SQLData['class']??null;
    }

    public function SetJudge(string $class):bool
    {
        if( $class != $this->SQLData['class'] && $class != '' )
        {
            //TODO : Check for installed
            if( !\Plugin::isClassName($class) )
            {
                return false;
            }
        }
        $this->UpdateSQLLazy('class',$class);
        return true;
    }

    //Get Problem Content
    public function GetTitle():string
    {
        return $this->SQLData['title'];
    }

    public function SetTitle(string $title):bool
    {
        if( strlen($title) > self::TITLE_LENTH_MAX )
        {
            return false;
        }
        $this->UpdateSQLLazy('title',$title);
        return true;
    }

    public function GetJudgeType():int
    {
        return $this->SQLData['judge_type'];
    }

    public function SetJudgeType(string $judge_type):bool
    {
        if( ProblemJudgeTypeEnum::isValidValue($judge_type) )
        {
            return false;
        }
        $this->UpdateSQLLazy('judge_type',$judge_type);
        return true;
    }

    static public function RenderString(string $str,int $type):string
    {
        global $_E;
        $res = '';
        switch($type)
        {
            case ProblemDescriptionEnum::HTML:
                $res = $str;
                break;
            case ProblemDescriptionEnum::MarkDown:
                require_once $_E['ROOT'].'/function/externals/Parsedown.php';
                $Parsedown = new \Parsedown();
                $res = $Parsedown->text($str);
                break;
            default:
                throw new \Exception('ProblemDescriptionEnum type error');
        }
        return $res;
    }

    private function RenderRowContentToFile()
    {
        $pid = $this->pid();
        $str = $this->GetRowContent();
        $res = self::RenderString($str,$this->config['content']['type']);
        file_put_contents(self::GetHttpFolder($pid).self::RENDERED_FILE,$res);
    }

    public function GetRenderedContent()
    {
        $pid = $this->pid();
        if( !file_exists(self::GetHttpFolder($pid).self::RENDERED_FILE) || $this->row_changed )
        {
            if( !$this->RenderRowContentToFile() )
            {
                return false;
            }
        }
        return file_get_contents(self::GetHttpFolder($pid).self::RENDERED_FILE);
    }

    public function GetRowContent()
    {  
        $pid = $this->pid();
        $path = self::GetHttpFolder($pid).self::ROW_FILE;
        if( !file_exists($path) || !is_file($path) )
            return '';
        return file_get_contents($path);
    }

    public function SetRowContent(string $str)
    {
        $pid = $this->pid();
        $path = self::GetHttpFolder($pid).self::ROW_FILE;
        file_put_contents($path,$str);
        $this->row_changed = true;
    }

    public function Update():bool
    {
        $this->UpdateSQL();
        if( $this->row_changed )
        {
            $this->RenderRowContentToFile();
            $this->row_changed = false;
        }
        return true;
    }
}
