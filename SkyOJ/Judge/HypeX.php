<?php namespace SkyOJ\Judge;

use \SkyOJ\Challenge\LanguageCode;

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

class HypeX extends Judge
{
    const VERSION = '0.1-alpha';
    const NAME = 'HypeX Judge Bridge';
    const DESCRIPTION = 'HypeX Judge Bridge';
    const COPYRIGHT = 'Judge - HypeX Copyright (C) 2016 PZ Read (MIT License)';

    private $m_judge;
    private $m_file_method;
    private $m_ssh_host;
    private $m_ssh_port;
    private $m_ssh_acct;
    private $m_ssh_pass;
    private $m_ssh_dir;
    private $m_data_dir;

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
    }

    public static function requiredFunctions():array
    {
        return ['curl_init','curl_setopt','curl_exec','curl_close','curl_close'];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
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
    private function putFileviaSSH(string $file_path,string $data):bool
    {
        if( empty($data) )$data = ' ';//Hack

        $host = $this->m_ssh_host;
        $port = $this->m_ssh_port;
        $acct = $this->m_ssh_acct;
        $pass = $this->m_ssh_pass;
        $dir  = $this->m_ssh_dir;

        $sftp = new \phpseclib\Net\SFTP($host,$port);
        if(!$sftp->login($acct,$pass))
        {
            return false;
        }
        return $sftp->put("{$dir}/{$file_path}",$data) !== false;
    }

    private function putCodeviaSSH(string $code,int $cid,string $type='cpp'):string
    {
        $res = $this->putFileviaSSH("{$cid}.{$type}",$code);
        if( $res==false )
        {
            throw new \Exception('Upload Code Error!');
        }

        return $this->m_data_dir."{$cid}.{$type}";
    }

    private static function get_json_main()
    {
        return new class{
            public $chal_id;
            public $code_path;
            public $res_path;
            public $comp_type;
            public $check_type;
            public $metadata;
            public $test = [];
        };
    }

    private static function get_json_test()
    {
        return new class{
            public $test_idx;
            public $timelimit;
            public $memlimit;
            public $metadata;
        };
    }

    private static function get_json_chalmeta()
    {
        return new class{
            public $redir_test;
            public $redir_check;
        };
    }

    public function getCompilerInfo()
    {
        return [
            [0, LanguageCode::C  , "gcc -std=c++11"],
            [1, LanguageCode::CPP, "g++ -std=c++14 -O2"],
            [1, LanguageCode::PYTHON3, "python3 | LANG=en_US.UTF-8"]
        ];
    }

    private function parseSKYOJJson($pjson,$chal,&$score)
    {
        $score = [];
        $json = self::get_json_main();
        $json->chal_id    = $chal->cid;
        $json->code_path  = $this->putCodeviaSSH($chal->code, $chal->cid);
        $json->res_path   = $this->m_data_dir."/problem/{$chal->pid}/res";
        $json->comp_type  = $pjson->compile;
        $json->check_type = $pjson->check;

        foreach ($pjson->test as $testdata) {
            $test = self::get_json_test();
            $test->test_idx = count($json->test);
            $score[$test->test_idx] = $testdata->weight;
            $test->timelimit = $pjson->timelimit;
            $test->memlimit  = $pjson->memlimit*10;
            $test->metadata = new class{public $data = [];};
            $test->metadata->data = $testdata->data;
            $json->test[] = $test;
        }

        $json->metadata = self::get_json_chalmeta();
        $json = json_encode($json);
        return $json;
    }

    public function judge(\SKYOJ\Challenge\Container $chal)
    {
        $pjson = json_decode($chal->problem()->getJudgeJson());
        if( $pjson === false )
            throw new JudgeException("Problem Json Ddcode Error pid:{$c->pid()}");
        
        $score = [];

        #put file to judge

        $json = $this->parseSKYOJJson($pjson,$chal,$score);
        
        $post = new HypeX_wspost($this->m_judge);
        $package =  $post->send($json);
        $package = json_decode($package);

        if( $package == false )
            return false;
        if( !isset($package->result) )
            return false;
        $res = []; 
        try{
            
            foreach( $package->result as $row )
            {
                $tmp = new \SKYOJ\Challenge\ChallengeTask();
                $tmp->taskid = $row->test_idx;
                $tmp->runtime= $row->runtime;//ms
                $tmp->mem    = $row->peakmem;//in KB
                $tmp->state  = ($row->state+1)*10; //To SKY Format Code..
                $tmp->score  = ($row->state==1) * $score[$tmp->taskid];  //sub score
                $tmp->msg    = $row->verdict[0];//judge message
                $res[] = $tmp;
            }
        }catch(\Exception $e){ //prevent judge return an empty json, let it make a CE
            return false;
        }
        return $res;
    }
}
