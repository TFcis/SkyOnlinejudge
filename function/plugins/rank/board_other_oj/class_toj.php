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
	private $usernalist = array();
	function __construct()
	{

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
	
	function preprocess($userlist,$problist)
	{
        global $_E;
        $reqtype = array();
        foreach($userlist as $uid)
        {
            if(!$this->checkid($uid))
                continue;
            $this->useraclist[$uid] = array();
            $this->usernalist[$uid] = array();
            $query['acct_id'] = $uid;
            
            
            $query['reqtype'] = 'AC';
            if( $aclist = $this->post($query) )
            {
                $this->useraclist[$uid]  = json_decode($aclist)->ac;
            }
            
            $query['reqtype'] = 'NA';
            if( $nalist = $this->post($query) )
            {
                $this->usernalist[$uid]  = json_decode($nalist)->na;
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
        elseif(in_array($pid,$this->usernalist[$uid]))
        {
            return 70;
        }
        else
        {
            return 0;
        }
    }
	
    function showname($str)
    {
        $pid = preg_replace('/[^0-9]*/','',$str);
        $str="<a href='http://toj.tfcis.org/oj/pro/$pid/' target='_blank'>TOJ $pid</a>";
        return $str;
    }
}