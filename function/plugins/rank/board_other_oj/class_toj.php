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
    
    function getuserinfo($uid)
    {
        $query['reqtype'] = 'INFO';
        $query['acct_id'] = $uid;
        $json = $this->post($query);
        if( $data = json_decode($json) )
            return $data;
        return false;
    }
    
    function account_detail($uid)
    {
        $data = $this->getuserinfo($uid);
        if( $data )
        {
            return "Your Nickname : <strong>".$data->nick."</strong>";
        }
        else
            return false;
    }
    
    function authenticate_message( $uid , $tojid )
    {
        $token= DB::loadcache( 'class_toj_authtoken' , $uid );
        if( !$token )
        {
            $token = substr(md5(uniqid(uniqid(),true)),1,8);
            DB::putcache('class_toj_authtoken',$token,10,$uid);
        }
        $msg = "請將TOJ的暱稱改為<b>$token</b>後，點擊驗證繼續操作。<br>驗證完畢後您可以修改回原暱稱";
        return $msg;
    }
    
    function authenticate( $uid , $tojid )
    {
        $res = array(false,'unknown');
        $token = DB::loadcache( 'class_toj_authtoken' , $uid );
        if( $token === false )
        {
            $res[1] = 'No TOKEN . Please reload page';
            return $res;
        }
        $data = $this->getuserinfo($tojid);
        if( $data && $data->nick == $token )
        {
            DB::deletecache( 'class_toj_authtoken' , $uid );
            return true;
        }
        $res[1] = '驗證錯誤，請重新嘗試 ('.$data->nick.')';
        return $res;
    }
}