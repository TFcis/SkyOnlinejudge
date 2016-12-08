<?php namespace SKYOJ;
/*
 * SKY Online Judge Site Core
 * 2016 Sky Online Judge Project
 */
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
//Load All Core
require_once 'Log.php';
require_once 'DB.php';

require_once 'userControl.php';
require_once 'renderCore.php';
require_once 'pluginsCore.php';
//Load Library
require_once 'function/common/encrypt.php';
require_once 'function/common/emnu.php';
require_once 'function/common/forminfo.php';
require_once 'function/common/common_object.php';
require_once 'Skyoj.lib.php';

final class _SkyOJ
{
    /**
     *  URL Handler
     *  TODO: User defined URI rewrite
     */
    private $uri_param = [];

    //TODO PHP7.1 :?bool
    public function UriParam(int $index)
    {
        return $this->uri_param[$index]??null;
    }

    private function UriHandler()
    {
        $path_info = $_SERVER['PATH_INFO']??'/';
        if( strlen($path_info) > 0 && $path_info[0] === '/' )
        {
            $path_info = substr($path_info, 1);
        }
        $this->uri_param = explode('/', $path_info);
    }

    public function uri(...$var):string
    {
        global $_E;
        $uri = $_E['SITEROOT'].'index.php';
        foreach($var as $data)
            $uri .= '/'.$data;
        return $uri;
    }

    //SkyOJ Not deal with init time error
    public function __construct()
    {
        global $_E;
        \LOG::intro();
        \DB::intro();
        \DB::query('SET NAMES UTF8');
        \userControl::intro();
        $this->UriHandler();
    }

    private $handle_list = [];
    private $default_handle = null;
    public function RegisterHandle(string $name,string $function,$file,bool $default = false)
    {
        $this->handle_list[$name] = [$function,$file];
        if( $default ){
            if( isset($this->default_handle) ){
                \Log::msg(Level::Debug,'RegisterHandle','Rewrite Default handle!');
            }
            $this->default_handle = $name;
        }
    }

    //Set HTML Title
    //Example : SetTitle('Login')
    //Show: Login - [Sitename]
    private $site_title = '';
    public function SetTitle(string $str)
    {
        $this->site_title = $str;
    }

    public function GetTitle():string
    {
        global $_E;
        if( empty($this->site_title) )
        {
            return $_E['site']['name'];
        }
        return "{$this->site_title} - {$_E['site']['name']}";
    }

    
    CONST OUTPUT_HTML = 1;
    CONST OUTPUT_API  = 2;
    CONST OUTPUT_HTML_BG = 3;
    private $output_mode = self::OUTPUT_HTML;

    public function setOutputMode(int $mode):bool
    {
        if( $this->output_mode == self::OUTPUT_HTML_BG )
        {
            if( $mode != self::OUTPUT_HTML_BG )
            {
                \Log::msg(\Level::Notice,"[SKYOJ] OUTPUT_HTML_BG cannot be changed!");
                return false;
            }
            return true;
        }
        switch($mode)
        {
            case self::OUTPUT_HTML:
            case self::OUTPUT_API:
                break;
            case self::OUTPUT_HTML_BG:
                ob_end_clean();
                header("Connection: close\r\n");
                header("Content-Encoding: none\r\n");
                ignore_user_abort(true);
                ob_start();
                break;
            default: NeverReach();
        }
        $this->output_mode = $mode;
        return true;
    }

    public function throwjson_keep($status, $data)
    {
        $str = json_encode(['status' => $status, 'data' => $data]);
        if( $this->output_mode == self::OUTPUT_HTML_BG )
        {
            echo $str;
            $this->flush();
        }
        else
        {
            exit($str);
        }
    }

    private $flush_called = false;
    public function flush()//end output but keep run php
    {
        $this->flush_called = true;
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
        ob_end_clean();
        session_write_close();
    }

    public function run()
    {
        try{
            if( empty($this->uri_param[0]) ){
                $this->uri_param[0] = $this->default_handle;
            }
            if( !isset($this->uri_param[0]) ){
                throw new \Exception('Cannot Get Default Handle!');
            }

            $param0 = $this->uri_param[0];
            if( !isset($this->handle_list[$param0]) ){
                throw new \Exception('No such Handle!'.$param0);
            }
            if( !empty($this->handle_list[$param0][1]) ){
                require_once($this->handle_list[$param0][1]);
            }
            if( !is_callable($this->handle_list[$param0][0]) ){
                throw new \Exception('No such Handle function!');
            }
            $this->handle_list[$param0][0]();
        }catch(\Throwable $e){
            switch( $this->output_mode )
            {
                case self::OUTPUT_HTML:
                case $this->flush_called==true:
                    echo $e->getMessage();
                    var_dump($e);
                    exit(0);
                case self::OUTPUT_API:
                case self::OUTPUT_HTML_BG:
            }
            
            echo $e->getMessage();
            var_dump($e);
            exit(0);
        }
    }

    private $sysvalue_cache = [];
    private $table_sysvalue = null;
    private function SysValueFullName(string $name,string $prefix):string
    {
        return $prefix.'$'.$name;
    }

    public function GetSysValue(string $name,string $prefix):string
    {
        $index = $this->SysValueFullName($name,$prefix);
        if( !isset($this->sysvalue_cache[$index]) )
        {
            if( !isset($this->table_sysvalue) )
                $this->table_sysvalue = \DB::tname('sysvalue');
        
            $data = \DB::fetchEx("SELECT `name`, `var` 
                                  FROM `{$this->table_sysvalue}`
                                  WHERE `name` = ?",$index);
            if( $data===false )
                \Log::msg(\Level::Warning,'Cannot get Sysvalue:'.$index);
            $this->sysvalue_cache[$index] = $data[$index];
        }
        return $this->sysvalue_cache[$index];
    }

    public function SetSysValue(string $name,string $prefix,string $value):bool
    {
        $index = $this->SysValueFullName($name,$prefix);
        if( !isset($this->table_sysvalue) )
            $this->table_sysvalue = \DB::tname('sysvalue');
        $res = \DB::queryEx("INSERT INTO {$this->table_sysvalue} (`name`, `var`) 
                             VALUES(?,?) 
                             ON DUPLICATE KEY UPDATE `var`=?",$index,$value,$value);
        if($res)$this->sysvalue_cache[$index] = $value;
        return $res!=false;
    }
}
$SkyOJ = new _SkyOJ();
