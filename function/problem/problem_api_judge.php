<?php namespace SKYOJ\Problem;
require 'Net/SFTP.php';
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class socket
{
    private $host;
    private $bind;
    private $port;
    private $socket;
    //private $connection;

    public function __construct($host, $port, $bind)
    {
        $this->host = $host;
        $this->bind = $bind;
        $this->port = $port;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function send($txt)
    {
        //socket_bind($this->socket,$this->bind);
        $connection = socket_connect($this->socket, $this->host, $this->port);
        if ($connection === false) {
            return false;
        }

        if (!socket_write($this->socket, $txt, strlen($txt))) {
            return false;
        }

        while ($result = socket_read($this->socket, 2048)) {
            return $result;
        }
    }
}

class post
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
        echo curl_error($ch);
        curl_close($ch);

        return $temp;
    }
}

class json_main
{
    public $chal_id;
    public $code_path;
    public $res_path;
    public $comp_type;
    public $check_type;
    public $metadata;
    public $test = [];
}

class json_test
{
    public $test_idx;
    public $timelimit;
    public $memlimit;
    public $metadata;
}

class json_testdata
{
    public $data = [];
}

class json_chalmeta
{
    public $redir_test;
    public $redir_check;
}

class json_redir_test
{
    public $testin;
    public $testout;
    public $pipein;
    public $pipeout;
}

class json_redir_check
{
    public $testin;
    public $ansin;
    public $pipein;
    public $pipeout;
}

class json_result
{
    public $chal_id;
    public $uid;
    public $verdict;
    public $state;
    public $result = [];
    public $score;
}

class json_resultdata
{
    public $test_idx;
    public $state;
    public $runtime;
    public $peakmem;
    public $verdict;
}

require_once($_E['ROOT'].'/function/challenge/challenge.lib.php');
function problem_api_judgeHandle()
{
    global $_G,$_E;
    $cid = \SKYOJ\safe_get('cid');
    try{
        $data = new \SKYOJ\Challenge\Challenge($cid);
        $res = $data->run_judge();

        if( $res === false )
            throw new \Exception('judge error');
        \SKYOJ\throwjson('SUCC',"Yeeee!");
    }catch(\Exception $e){
        \SKYOJ\throwjson('error',$e->getMessage());
    }
}