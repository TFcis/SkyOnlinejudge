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
