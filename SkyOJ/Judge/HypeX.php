<?php namespace SkyOJ\Judge;

use \SkyOJ\Challenge\LanguageCode;
use \SkyOJ\Challenge\ResultCode;
use \SkyOJ\Challenge\Result;
use \SkyOJ\Challenge\Package;

class HypeX_wspost
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function send($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $temp = curl_exec($ch);
        //echo curl_error($ch);
        curl_close($ch);

        return $temp;
    }
}

class HypeX_FileMethodEnum extends \SkyOJ\Helper\Enum
{
    const SFTP = 0;
}

interface RemoteFileAction
{
    public function isConnected():bool;
}

class SSH implements RemoteFileAction
{
    private $m_ssh;
    private $base_dir;
    private $logined;
    public function __construct($address, $port, $acct, $pass, $base_dir)
    {
        $this->m_ssh = new \phpseclib\Net\SFTP($address, $port);
        if( !$this->m_ssh->login($acct, $pass) )
        {
            $this->logined = false;
            return;
        }
        if( !$this->m_ssh->chdir($base_dir) )
        {
            $this->logined = false;
            return;
        }
        $this->logined = true;
    }

    public function isConnected():bool
    {
        return $this->logined;
    }

    public function mkdir(string $dir):bool
    {
        if( $this->m_ssh->is_dir($dir) )
            return true;
        return $this->m_ssh->mkdir($dir, -1, true);
    }

    public function putLocalFile(string $remote, string $local):bool
    {
        if( !is_file($local) )
        {
            return false;
        }
        return $this->m_ssh->put($remote, $local, \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE);
    }

    public function putStringFile(string $remote, string $string):bool
    {
        if( strlen($string) == 0 )
        {
            $string = ' ';
        }
        return $this->m_ssh->put($remote, $string);
    }
}

// API Document
// http://judge-hypex.readthedocs.io/en/latest/TestOJCompatible.html#test-json-format

class CompTypeEnum
{
    const GPP      = 'g++';
    const CLANGPP  = 'clang++';
    const MAKEFILE = 'makefile';
    const PYTHON3  = 'python3';
}

class CheckTypeEnum
{
    const DIFF     = 'diff';
    const IOREDIR  = 'ioredir';
}

class ChallengeRequestJson
{
    public $chal_id;
    public $code_path;
    public $res_path;
    public $comp_type;
    public $check_type;
    public $metadata;
    public $test = [];
}

class TestJson
{
    public $test_idx;
    public $timelimit;
    public $memlimit;
    public $metadata;
}

class TestMetaJson
{
    public $data = [];
}

class ChalMetaJson
{
    public $redir_test;
    public $redir_check;
}

class HypeX extends Judge
{
    private $m_judge;
    private $m_file_method;
    private $m_ssh_host;
    private $m_ssh_port;
    private $m_ssh_acct;
    private $m_ssh_pass;
    private $m_ssh_dir;
    private $m_data_dir;

    private $m_storage;

    public function __construct($profile_json)
    {
        $data = json_decode($profile_json,true);
        $this->m_judge = $data['judge'];
        $this->m_file_method = $data['file_method'];
        $this->m_ssh_host    = $data['ssh_host']??'';
        $this->m_ssh_port    = $data['ssh_port']??'';
        $this->m_ssh_acct    = $data['ssh_acct']??'';
        $this->m_ssh_pass    = $data['ssh_pass']??'';
        $this->m_ssh_dir     = $data['ssh_dir']??'';
        $this->m_data_dir    = $data['data_dir'];
        $this->m_storage     = null; // it is lazy load
    }

    public function connectStorage()
    {
        if( isset($this->m_storage) )
            return ;

        switch($this->m_file_method)
        {
            case HypeX_FileMethodEnum::SFTP:
            {
                $this->m_storage = new SSH($this->m_ssh_host, $this->m_ssh_port, $this->m_ssh_acct, $this->m_ssh_pass, $this->m_ssh_dir);
                break;
            }
            default:
            $this->m_storage = null;
        }
        if( !isset($this->m_storage) || !$this->m_storage->isConnected() )
            throw new JudgeException('Can not connect remote server');
    }

    public static function installForm($oldprofile = null):array
    {

        return [
            'data' => [
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'judge','value'=>'','placeholder'=>'IP:Port/reqjudg','option'=>['help_text'=>'Judge 請求連結']]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_SELECT(['name'=>'file_method','key-pair'=>
                            HypeX_FileMethodEnum::getConstants()
                            ,'option' => ['help_text' => '資料放置方法','default' => 0]]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_DIV(['name'=>'','option'=>['html'=>"使用SFTP連線需要設定帳號密碼，登入後會將資料放置於指定的資料夾下，未設定毋需填寫"]]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_host','value'=>'','option'=>['help_text'=>'SSH Host']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_port','value'=>'','option'=>['help_text'=>'SSH Prot']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_acct','value'=>'','option'=>['help_text'=>'SSH 連線帳號']]),
                new \SKYOJ\HTML_INPUT_PASSWORD(['name'=>'ssh_pass','value'=>'','option'=>['help_text'=>'SSH 連線密碼']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_dir','value'=>'','option'=>['help_text'=>'放置資料夾']]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'data_dir','value'=>'','required'=>'required','placeholder'=>'/home/user/uploads/ ; C:\\uploads\\','option'=>['help_text'=>'上傳資料夾完整位置']]),
            ]
        ];
    }

    private static function postval($post,$col)
    {
        if( !isset($post[$col]) || !is_string($post[$col]) )
            return null;
        return $post[$col];
    }

    public static function checkProfile($post, &$msg)
    {
        $judge   = self::postval($post,'judge');
        $file_method = self::postval($post,'file_method');
        $ssh_host = self::postval($post,'ssh_host');
        $ssh_port = self::postval($post,'ssh_port');
        $ssh_acct = self::postval($post,'ssh_acct');
        $ssh_pass = self::postval($post,'ssh_pass')??'';
        $ssh_dir  = self::postval($post,'ssh_dir')??'';
        $data_dir = self::postval($post,'data_dir');

        try
        {
            if( !isset($file_method, $data_dir) )
                throw new \Exception('缺少必要參數');
    
            switch( $file_method )
            {
                case HypeX_FileMethodEnum::SFTP:
                    if( !isset($ssh_acct, $ssh_host, $ssh_port) )
                        throw new \Exception('缺少SSH連線資訊');

                    if( !class_exists('phpseclib\\Net\\SFTP') )
                    {
                        throw new \Exception('請確認phpseclib安裝(composer install)，無法載入phpseclib\\Net\\SFTP');
                    }

                    $sftp = new \phpseclib\Net\SFTP($ssh_host,$ssh_port);
                    if( !$sftp->login($ssh_acct,$ssh_pass) ) throw new \Exception('SFTP 無法登入');
                    if( !$sftp->put($ssh_dir.'/HypeX'," ") ) throw new \Exception('SFTP 權限不足(put)');
                    $sftp->mkdir($ssh_dir.'/'."problem");
                    if( !$sftp->put($ssh_dir.'/problem/HypeX'," ") ) throw new \Exception('SFTP 權限不足(put)');
                    break;
                default: throw new \Exception('file_method error');
            }

            #save all val;
            $val = ['judge','file_method','ssh_host','ssh_port','ssh_acct','ssh_pass','ssh_dir','data_dir'];
            $json = [];
            foreach($val as $v)
            {
                $json[$v] = $$v;
            }
            return json_encode($json);
        }
        catch(\Exception $e)
        {
            $msg = $e->getMessage();
            return false;
        }
    }

    public function getCompilerInfo()
    {
        return [
            [0, LanguageCode::CPP  , "gcc -std=c++11 -O2"],
            [2, LanguageCode::PYTHON3, "python3 | LANG=en_US.UTF-8"]
        ];
    }

    private function getCompilerInfoByInfo($id)
    {
        switch($id)
        {
            case 0: return CompTypeEnum::GPP;
            case 2: return CompTypeEnum::PYTHON3;
        }
        return CompTypeEnum::GPP;
    }

    private function parseSKYOJJson($chal)
    {
        $json = new ChallengeRequestJson();
        $json->chal_id    = (int)$chal->cid;
        $json->code_path  = $this->m_data_dir.'/'.$this->makeSourceFilename($chal->cid, $chal->language);
        $json->res_path   = $this->m_data_dir."/problem/{$chal->pid}/";
        //TODO: check special judge such as MAKEFILE
        $json->comp_type  = $this->getCompilerInfoByInfo($chal->compiler);
        //TODO Support ID redir
        $json->check_type = CheckTypeEnum::DIFF;

        //TODO Support ID redir
        $json->metadata = null;

        //Add testcases
        foreach( $chal->problem()->getTestdataInfo() as $data )
        {
            $test = new TestJson();
            $test->test_idx  = $data->id();
            $test->timelimit = $data->runtime_limit();
            $test->memlimit  = $data->memory_limit();
            $test->metadata  = new TestMetaJson();
            $test->metadata->data[] = $data->id();

            $json->test[] = $test;
        }

        $json->metadata = new ChalMetaJson();
        $json = json_encode($json);
        return $json;
    }

    private function makeSourceFilename(int $id, $lang)
    {
        $ext = 'txt';
        switch($lang)
        {
            case LanguageCode::C:
            {
                $ext = 'c';
                break;
            }
            case LanguageCode::CPP:
            {
                $ext = 'cpp';
                break;
            }
            case LanguageCode::PYTHON3:
            {
                $ext = 'py';
                break;
            }
        }
        return "{$id}.{$ext}";
    }

    private function hypexResultToSkyOJ(int $code)
    {
        //From https://github.com/pzread/judge/blob/master/StdChal.py#L16
        switch($code)
        {
            case 0: return ResultCode::WAIT;
            case 1: return ResultCode::AC;
            case 2: return ResultCode::WA;
            case 3: return ResultCode::RE;
            case 4: return ResultCode::TLE;
            case 5: return ResultCode::MLE;
            case 6: return ResultCode::CE;
            default : return ResultCode::JE;
        }
    }

    public function judge(\SKYOJ\Challenge\Container $chal)
    {
        #put file to judge
        $this->connectStorage();
        $soucecodename = $this->makeSourceFilename($chal->cid, $chal->language);
        if( !$this->m_storage->putStringFile($soucecodename, $chal->code) );

        $json = $this->parseSKYOJJson($chal);

        $post = new HypeX_wspost($this->m_judge);
        $package =  $post->send($json);
        $package = json_decode($package);

        if( $package == false )
            return null;
        if( !isset($package->result) )
            return null;
        
        $res = new Result; 

        try
        {
            foreach( $package->result as $row )
            {
                $tmp = new Package;
                $tmp->id        = $row->test_idx;
                $tmp->runtime   = $row->runtime;
                $tmp->memory    = $row->peakmem;
                $tmp->result_code = $this->hypexResultToSkyOJ($row->state);
                $tmp->message   = $row->verdict[0];//judge message
                $res->tasks   []= $tmp;
            }
        }
        catch(\Exception $e) //prevent judge return an empty json, let it make a CE
        {
            return null;
        }
        return $res;
    }

    public function syncTestdata(\SKYOJ\Problem\Container $problem):bool
    {
        $this->connectStorage();
        $testdata = $problem->getTestdataInfo();
        $pid   = $problem->pid;
        $problem_testdata_dir = 'problem/'.$pid.'/testdata';

        if( !$this->m_storage->mkdir($problem_testdata_dir) )
            return false;

        $res = true;
        foreach( $testdata as $row )
        {
            $tid = $row->id();
            $res &= $this->m_storage->putLocalFile($problem_testdata_dir."/{$tid}.in",  $row->input(true));
            $res &= $this->m_storage->putLocalFile($problem_testdata_dir."/{$tid}.out", $row->output(true));
        }

        return $res;
    }
}
