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
        \userControl::intro();
        \DB::query('SET NAMES UTF8');
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
            echo $e->getMessage();
            var_dump($e);
            exit(0);
        }
    }
}
$SkyOJ = new _SkyOJ();
