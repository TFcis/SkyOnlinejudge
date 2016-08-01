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
    const MarkDown  = '1';
    const HTML      = '2';
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
        $tproblem = \DB::tname('problem');
        return \DB::queryEx("UPDATE `{$tproblem}` SET `title`= ? WHERE `pid`=?",$title,$this->pid())!==false;
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
        if( !file_exist(self::GetHttpFolder($pid).RENDERED_FILE) || $this->row_changed )
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

    public function Update()
    {
        if( $this->row_changed )
        {
            $this->RenderRowContentToFile();
            $this->row_changed = false;
        }
    }
}
