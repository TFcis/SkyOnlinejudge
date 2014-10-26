<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class class_toj{
    public $version = '1.1';
    public $name = 'Toj capturer';
	public $description = 'TOJ capturer';
	public $copyright = 'TFcis';
	public $pattern = "/^toj[0-9]+$/i";
	private $api = 'http://210.70.137.215/oj/be/api';
	private $useraclist = array();
	function __construct()
	{
	    if(!function_exists('pcntl_fork'))
	    {
	        $this->description .= "(安裝pcntl來取得更好的效率!)";
	    }
	    else
	    {
	        $this->description .= "(使用pcntl加速中!)";
	    }
	}
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `tojtest_ojlist`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_toj','TNFSH Online Judge','TOJ uid',1)");
	    //set SQL
	}
	
	function checkid($id)
	{
	    $id = (string)$id;
	    return preg_match('/^[1-9]+[0-9]*$/',$id);
	}
	
	function post($data)
	{
	    $context['http'] = array (
			'timeout'   => 60,
			'method'	=> 'POST',
			'content'   => http_build_query($data, '', '&'),
		);
		$response = @file_get_contents('http://210.70.137.215/oj/be/api', false, stream_context_create($context));
		return $response;
	}
	
	function back($uid,$query)
	{
	    global $_E;
	    if( DB::loadcache("class_toj_work_$uid") ){
            return ;
        }
        if( function_exists('pcntl_fork') )
	        $pid = pcntl_fork();
	    else
	        $pid = 'NO_PCNTL';
        if ( $pid === -1 ){
            $pid = 'NO_PCNTL';
        }
        if( $pid!==0 && $pid!=='NO_PCNTL' ){
            return ;
        }

        DB::putcache("class_toj_work_$uid",'work',86400);
        if( $aclist = $this->post($query) )
        {
            
            $_E['template']['dbg'].="$uid download from toj<br>";
            $this->useraclist[$uid]  = json_decode($aclist)->ac;
            DB::putcache(   "class_toj_uid_$uid",
                            array(time()+rand(120,420),$this->useraclist[$uid]),86400);
            DB::deletecache("class_toj_work_$uid");
        }
        if($pid === 'NO_PCNTL'){
            return ;
        }
        sleep(5);
        exit('E');
	}
	function preprocess($userlist,$problist)
	{
	    global $_E;
	    $query = array('reqtype' => 'AC','acct_id' => 0 );
	    foreach($userlist as $uid)
	    {
	        $rebuild = true;
	        if(!$this->checkid($uid))
	            continue;
	        $query['acct_id'] = $uid;
	        $cache = DB::loadcache("class_toj_uid_$uid");
	        if( $cache !== false )
	        {
	            $_E['template']['dbg'].="$uid load form cache<br>";
	            $this->useraclist[$uid] = $cache[1];
	            if( $cache[0]>time())
	            {
	                $rebuild=false;
	            }
	        }
	        else
	        {
	            $this->useraclist[$uid] = array();
	        }
	        
	        if( $rebuild )
	        {
	            $_E['template']['dbg'].="$uid Build!<br>";
	            $this->back($uid,$query);
	        }
	    }
	}
	
	function query($uid,$pid)
	{
	    $pid = preg_replace('/[^0-9]*/','',$pid);
	    if(in_array($pid,$this->useraclist[$uid]))
	    {
	        return 90;
	    }
	    else
	    {
	        return 0;
	    }
	}
	
	function showname($str)
	{
	    $pid = preg_replace('/[^0-9]*/','',$str);
	    $str="<a style='color:rgb(255,246,157)' href='http://toj.tfcis.org/oj/pro/$pid/' target='_blank'>toj $pid</a>";
	    return $str;
	}
}