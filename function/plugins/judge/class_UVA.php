<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_UVA extends Judge
{
    const VERSION = '0.1-alpha';
    const NAME = 'UVA Judge Bridge';
    const DESCRIPTION = 'UVA Judge Bridge';
    const COPYRIGHT = 'SKY Online Judge 2017';

    public static function requiredFunctions():array
    {
        return [];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
    }

    public static function installForm():array
    {
        return [
            'data' => [
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'username','value'=>self::getval('username'),'option'=>['help_text'=>'UVA username']]),
                new \SKYOJ\HTML_INPUT_TEXT(['name'=>'password','value'=>self::getval('password'),'option'=>['help_text'=>'UVA password']]),
            ]
        ];
    }

    private static $cookie_file;
    private static function load_cookie()
    {
        if( isset(self::$cookie_file) )
            return ;
        
        global $SkyOJ;

        self::$cookie_file = tempnam(sys_get_temp_dir(), 'CookieUVA');
        $val = $SkyOJ->cache_pool->get('UVA_Cookie','');
        file_put_contents(self::$cookie_file,$val);
    }

    private static function login(string $username,string $password)
    {
        global $SkyOJ,$_E;
        self::load_cookie();
        
        $html = \SkyOJ\Core\Net\Get::send("https://uva.onlinejudge.org/",[],self::$cookie_file);
        if( strpos($html,"Logout") !== false ) return true;

        //UVA's bug... load again
        $html = \SkyOJ\Core\Net\Get::send("https://uva.onlinejudge.org/",[],self::$cookie_file);

        $data = [];
        $matched = [];
        $preg_hidden = '/<input type=\"hidden\" name=\"([\s\S]*?)\" value=\"([\s\S]*?)\" \/>/';
        preg_match_all($preg_hidden,$html,$matched,PREG_SET_ORDER);
        foreach( $matched as $row )
        {
            $column = $row[1];
            $value = $row[2];
            if( $column == "cx" ) continue;
            if( $column == "ie" ) continue;
            if( $column == "q" ) continue;
            if( $column == "sa" ) continue;
            if( $column == "option" ) continue;
            $data[$column] = $value;
        }
        $data["remember"] = "yes";
        $data["username"] = $username;
        $data["passwd"] = $password;

        $html = \SkyOJ\Core\Net\Post::send("https://uva.onlinejudge.org/index.php?option=com_comprofiler&task=login",$data,self::$cookie_file);

        $SkyOJ->cache_pool->set('UVA_Cookie',file_get_contents(self::$cookie_file),time()+8640000);
        return strpos($html,"Logout") !== false;
    }

    private static function uname2uid(string $name):int
    {
        $response = trim(@file_get_contents("https://uhunt.onlinejudge.org/api/uname2uid/".$name));
        if( !\SKYOJ\check_tocint($response) )
            return 0;
        return (int)$response;
    }

    public static function install(&$msg):bool
    {
        $username = \SKYOJ\safe_post('username');
        $password = \SKYOJ\safe_post('password');
        $uvaid = 0;
        try{
            if( !isset($username,$password) )
                throw new \Exception('缺少必要參數');

            global $SkyOJ;
            $SkyOJ->cache_pool->set('UVA_Cookie','',time()+8640000); //Remove cache
            if( !self::login($username,$password) )
                throw new \Exception('UVA 測試登入失敗');

            $uvaid = self::uname2uid($username);
            if( $uvaid === 0 )
                throw new \Exception('UVA ID API ERROR');
            #save all val
            $val = ['username','password','uvaid'];
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

    public function get_compiler()
    {
        return [
            'cpp11' => 'C++11 5.3.0 - GNU C++ Compiler with options: -lm -lcrypt -O2 -std=c++11 -pipe -DONLINE_JUDGE',
            'cpp' => 'C++ 5.3.0 - GNU C++ Compiler with options: -lm -lcrypt -O2 -pipe -DONLINE_JUDGE',
            'c' => 'ANSI C 5.3.0 - GNU C Compiler with options: -lm -lcrypt -O2 -pipe -ansi -DONLINE_JUDGE',
        ];
    }

    private function compilerid(string $name)
    {
        switch( $name )
        {
            case 'cpp11': return 5;
            case 'cpp':   return 3;
            case 'c':     return 1;
        }
        return null;
    }

    public function wait(int $submitid,int $times = 6,int $hold = 10)
    {
        $uid = self::getval('uvaid');
        $qsid = $submitid-1;
        if( $times <= 0 )
            return null;

        $uvaverdict = null;
        $first = false;
        while( $times-- )
        {
            if( !$first )
                sleep($hold);
            $first = false;

            $data = json_decode(file_get_contents("https://uhunt.onlinejudge.org/api/subs-user/".$uid.'/'.$qsid));
            if( $data === false )
                continue;

            $uvaverdict = $data->subs[0];
            if( $uvaverdict[0] != $submitid )
                return null;

            if( $uvaverdict[2]!==0 && $uvaverdict[2]!==20 )
                break;
        }
        return $uvaverdict;
    }

    private function uvavid2skyojid(int $vid)
    {
        switch($vid)
        {
            case 10: return \SKYOJ\RESULTCODE::JE;
            case 15: return \SKYOJ\RESULTCODE::JE;
            case 20: return \SKYOJ\RESULTCODE::JE;
            case 30: return \SKYOJ\RESULTCODE::CE;
            case 35: return \SKYOJ\RESULTCODE::RF;
            case 40: return \SKYOJ\RESULTCODE::RE;
            case 45: return \SKYOJ\RESULTCODE::OLE;
            case 50: return \SKYOJ\RESULTCODE::TLE;
            case 60: return \SKYOJ\RESULTCODE::MLE;
            case 70: return \SKYOJ\RESULTCODE::WA;
            case 80: return \SKYOJ\RESULTCODE::PE;
            case 90: return \SKYOJ\RESULTCODE::AC;
        }
        return \SKYOJ\RESULTCODE::JE;
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

        $pid = $pjson->pid;
        $compiler = $this->compilerid($c->compiler);

        if( $compiler === null )
            return false;

        if( !self::login( self::getval('username'),self::getval('password') ) )
            return false;

        $data = [];
        $data["problemid"] = "";
        $data["category"] = "";
        $data["localid"] = $pid;
        $data["language"] = $compiler;
        $data["code"] = $c->code();
        $data["codeupl"] = "";

        $html = \SkyOJ\Core\Net\Post::send("https://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=25&page=save_submission",$data,self::$cookie_file);

        $matched = [];
        preg_match('/with\+ID\+(\d+)/',$html,$matched);
        $submit_id = $matched[1]??'';

        if( $submit_id === '' ) return false;

        $res = [];
        try{
            $val = $this->wait($submit_id)??[];
            $tmp = new \SKYOJ\Challenge\ChallengeTask();
            $tmp->taskid = 0;
            $tmp->runtime= $val[3]??0;//ms
            $tmp->mem    = 0;//in KB
            $tmp->state  = $this->uvavid2skyojid($val[2]??0);
            $tmp->score  = ($tmp->state==\SKYOJ\RESULTCODE::AC) * 100;
            $tmp->msg    = "UVA Submit id = ".$submit_id;
            $res[] = $tmp;
        }catch(\Exception $e){
            \Log::msg(\Level::Error, 'UVA ERROR : ',$e->getMessage(),$val);
            return false;
        }
        return $res;
    }
}
