<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

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

class HypeX_FileMethodEnum extends \SKYOJ\BasicEnum{
    const SFTP = 0;
    const Local = 1;
}

class class_HypeX extends Judge
{
    const VERSION = '0.1-alpha';
    const NAME = 'HypeX Judge Bridge';
    const DESCRIPTION = 'HypeX Judge Bridge';
    const COPYRIGHT = 'Judge - HypeX Copyright (C) 2016 PZ Read (MIT License)';

    public static function requiredFunctions():array
    {
        return ['curl_init','curl_setopt','curl_exec','curl_close','curl_close'];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
    }

    public static function installForm():array
    {
        return [
            'data' => [
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'judge','value'=>self::getval('judge'),'placeholder'=>'IP:Port/reqjudg','option'=>['help_text'=>'Judge 請求連結']]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_SELECT(['name'=>'file_method','key-pair'=>
                            \HypeX_FileMethodEnum::getConstants()
                            ,'option' => ['help_text' => '資料放置方法','default' => self::getval('file_method')]]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_DIV(['name'=>'','option'=>['html'=>"使用SFTP連線需要設定帳號密碼，登入後會將資料放置於指定的資料夾下，未設定毋需填寫"]]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_host','value'=>self::getval('ssh_host'),'option'=>['help_text'=>'SSH Host']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_port','value'=>self::getval('ssh_port'),'option'=>['help_text'=>'SSH Prot']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_acct','value'=>self::getval('ssh_acct'),'option'=>['help_text'=>'SSH 連線帳號']]),
                new \SKYOJ\HTML_INPUT_PASSWORD(['name'=>'ssh_pass','value'=>self::getval('ssh_pass'),'option'=>['help_text'=>'SSH 連線密碼']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'ssh_dir','value'=>self::getval('ssh_dir'),'option'=>['help_text'=>'放置資料夾']]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'data_dir','value'=>self::getval('data_dir'),'required'=>'required','placeholder'=>'/home/user/uploads/ ; C:\\uploads\\','option'=>['help_text'=>'上傳資料夾完整位置']]),
                new \SKYOJ\HTML_HR(),
                new \SKYOJ\HTML_INPUT_DIV(['name'=>'','option'=>['html'=>"SSH 功能使用phpseclib 1，請指定該該函數庫位置"]]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'phpseclib_dir','value'=>self::getval('phpseclib_dir'),'option'=>['help_text'=>'phpseclib 位置']]),
            ]
        ];
    }

    public static function install(&$msg):bool
    {
        $judge = \SKYOJ\safe_post('judge');
        $file_method = \SKYOJ\safe_post('file_method');
        $ssh_host = \SKYOJ\safe_post('ssh_host');
        $ssh_port = \SKYOJ\safe_post('ssh_port');
        $ssh_acct = \SKYOJ\safe_post('ssh_acct');
        $ssh_pass = \SKYOJ\safe_post('ssh_pass')??'';
        $ssh_dir  = \SKYOJ\safe_post('ssh_dir')??'';
        $data_dir = \SKYOJ\safe_post('data_dir');
        $phpseclib_dir = \SKYOJ\safe_post('phpseclib_dir')??'';
        try{
            if( !isset($file_method,$data_dir) )
                throw new \Exception('缺少必要參數');
            switch( $file_method )
            {
                case HypeX_FileMethodEnum::SFTP:
                    if( !isset($ssh_acct,$ssh_host,$ssh_port) )
                        throw new \Exception('缺少SSH連線資訊');
                    
                    #Try to load phpseclib
                    set_include_path(get_include_path().PATH_SEPARATOR.$phpseclib_dir);
                    //if( !file_exists('phpseclib/Net/SSH2.php') )
                    //    throw new \Exception('請確認phpseclib安裝位置');
                    require_once 'Net/SFTP.php';

                    if( !class_exists('\\Net_SFTP') )
                        throw new \Exception('請確認phpseclib安裝錯誤  無法載入\\Net_SFTP');

                    $sftp = new \Net_SFTP($ssh_host,$ssh_port);
                    if( !$sftp->login($ssh_acct,$ssh_pass) ) throw new \Exception('SFTP 無法登入');
                    if( !$sftp->put($ssh_dir.'/HypeX'," ") ) throw new \Exception('SFTP 權限不足(put)');
                    $sftp->mkdir($ssh_dir.'/'."problem");
                    //if( !$sftp->mkdir($ssh_dir.'/'."problem") ) throw new \Exception('SFTP 權限不足(mkdir)');
                    if( !$sftp->put($ssh_dir.'/problem/HypeX'," ") ) throw new \Exception('SFTP 權限不足(put)');
                    break;
                default: throw new \Exception('file_method error');
            }

            #save all val;
            $val = ['judge','file_method','ssh_host','ssh_port','ssh_acct','ssh_pass','ssh_dir','data_dir','phpseclib_dir'];
            foreach($val as $v)
            {
                if( !self::setval($v,$$v) )
                    throw new \Exception('SQL error');
            }
            
        }catch(\Exception $e){
            $msg = $e->getMessage();
            return false;
        }

        return true;
    }
    private function putFileviaSSH(string $file_path,string $data):bool
    {
        if( empty($data) )$data = ' ';//Hack

        static $preload_class = false;
        if( !$preload_class )
        {
            $phpseclib_path = self::getval('phpseclib_dir');
            set_include_path(get_include_path().PATH_SEPARATOR.$phpseclib_path);
            require_once 'Net/SFTP.php';
        }
        $host = self::getval('ssh_host');
        $port = self::getval('ssh_port');
        $acct = self::getval('ssh_acct');
        $pass = self::getval('ssh_pass');
        $dir  = self::getval('ssh_dir');

        $sftp = new \Net_SFTP($host,$port);
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

        return self::getval('data_dir')."{$cid}.{$type}";
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

    public function judge(\SKYOJ\Challenge\Challenge $c)
    {
        $pjson_path = \SKYOJ\Problem::GetProblemBaseFolder($c->pid())."/{$c->pid()}.json";
        if( !file_exists($pjson_path) )
        {
            \Log::msg(\Level::Error,"Cannot Load Problem Json pid:{$c->pid()}");
            return false;
        }

        $pjson = json_decode(file_get_contents($pjson_path));
        if( $pjson === false )
        {
            \Log::msg(\Level::Error,"Problem Json Ddcode Error pid:{$c->pid()}");
            return false;
        }

        $score = [];
        $json = self::get_json_main();
        $json->chal_id = $c->cid();
        #put file to judge
        $json->code_path = $this->putCodeviaSSH($c->code(),$c->cid());
        $json->res_path  = self::getval('data_dir')."/problem/{$c->pid()}/res";
        $json->comp_type = "g++";
        $json->check_type = "diff";

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
        $post = new HypeX_wspost(self::getval('judge'));
        
        $package =  $post->send($json);
        $package = json_decode($package);
        if( $package == false )
            return false;
        
        $res = [];
        foreach( $package->result as $row )
        {
            $tmp = new \SKYOJ\Challenge\ChallengeTask();
            $tmp->taskid = $row->test_idx;
            $tmp->runtime= $row->runtime;//ms
            $tmp->mem    = $row->peakmem;//in KB
            $tmp->state  = $row->state; //AC WA..
            $tmp->score  = ($row->state==1) * $score[$tmp->taskid];  //sub score
            $tmp->msg    = $row->verdict[0];//judge message
            $res[] = $tmp;
        }
        return $res;
    }
}
