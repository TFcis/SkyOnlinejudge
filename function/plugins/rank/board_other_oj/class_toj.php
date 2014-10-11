<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class class_toj{
    public $version = '1.0';
    public $name = 'Toj capturer';
	public $description = 'TOJ capturer for test';
	public $copyright = 'test by LFsWang';
	public $pattern = "/^toj[0-9]+$/";
	private $api = 'http://210.70.137.215/oj/be/api';
	private $useraclist = array();
	
	function post($data)
	{
	    $context['http'] = array (
			'timeout'   => 60,
			'method'	=> 'POST',
			'content'   => http_build_query($data, '', '&'),
		);
		$response = file_get_contents('http://210.70.137.215/oj/be/api', false, stream_context_create($context));
		return $response;
	}
	
	function preprocess($userlist,$problist)
	{
	    global $_E;
	    $query = array('reqtype' => 'AC','acct_id' => 0 );
	    foreach($userlist as $uid)
	    {
	        $query['acct_id'] = $uid;
	        if( isset($_SESSION['cache']['toj'][$uid]) &&
	                  $_SESSION['cache']['toj'][$uid]['time']>time())
	        {
	            $_E['template']['dbg'].="$uid load form cache<br>";
	            $this->useraclist[$uid] = $_SESSION['cache']['toj'][$uid]['data'];
	        }
	        elseif( $aclist = $this->post($query) )
	        {
	            $_E['template']['dbg'].="$uid download from toj<br>";
	            $this->useraclist[$uid]  = json_decode($aclist)->ac;
	            $_SESSION['cache']['toj'][$uid] = array();
	            $_SESSION['cache']['toj'][$uid]['time'] = time()+rand(30,120);
	            $_SESSION['cache']['toj'][$uid]['data'] = $this->useraclist[$uid];
	        }
	    }
	    //var_dump($this->useraclist);
	}
	function query($uid,$pid)
	{
	    $pid = preg_replace('/[^0-9]*/','',$pid);
	    if(in_array($pid,$this->useraclist[$uid]))
	    {
	        return 9;
	    }
	    else
	    {
	        return 0;
	    }
	}
}