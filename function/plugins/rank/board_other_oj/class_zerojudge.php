<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_zerojudge{
    public $version = '1.0';
    public $name = 'ZJ capturer';
    public $description = 'Zerojudge capturer';
    public $copyright = 'by ECHO_STATS';
    public $pattern = "/^zj:[a-z]{1}[0-9]+$/";
    private $cookiefile;
    private $loginflag = false ;
    private $zjcore;

    function __construct()
    {
        $this->zjcore = new zjcore;
        $this->zjcore->websiteurl = "http://zerojudge.tw/";
        $this->zjcore->classname  = "class_zerojudge";
        $this->cookiefile = new privatedata();
    }
	
    function httpRequest( $url , $post = null , $usepost =true )
    {
        if( is_array($post) )
        {
            ksort( $post );
            $post = http_build_query( $post );
        }
        
        $ch = curl_init();
        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_ENCODING, "UTF-8" );
        if($usepost)
        {
            curl_setopt( $ch , CURLOPT_POST, true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $post );
        }
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );
        curl_setopt ($ch , CURLOPT_COOKIEFILE, $this->cookiefile->name() );
        curl_setopt ($ch , CURLOPT_COOKIEJAR , $this->cookiefile->name() );
        
        $data = curl_exec($ch);
        curl_close($ch);
        if(!$data)
        {
            return false;
        }
        return $data;
    }
	
    function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
                (`id`, `class`, `name`, `description`, `available`) VALUES
                (NULL,'class_zerojudge','Zerojudge','Account Name',1)");
    }
	
    function checkid($id)
    {
        return $this->zjcore->checkid($id);
    }
	
    function preprocess($userlist,$problems)
    {
        global $_E;
        if( $this->loginflag === false )
        {
            $cont = $this->httpRequest('zerojudge.tw/Login',null,false);
            if( preg_match('/name="token" value="([^"]+)/',$cont,$res) )
            {
                $token = $res[1];
                $cont = $this->httpRequest('zerojudge.tw/Login',array(  'account' => 'tester123123' , 
                                                                        'passwd'  => '123123' ,
                                                                        'returnPage' => '/' ,
                                                                        'token'   => $token ));
            }
            $this->loginflag = true;
        }
        foreach($userlist as $user)
        {
            if( !$this->checkid($user) ){
	            continue;
            }
            if( $res = DB::loadcache("class_zerojudge_$user") && false )
            {
                //.....
            }
            else
            {
                $res = $this->httpRequest("zerojudge.tw/UserStatistic?account=".$user,false,false);
                $res = str_replace(array("\r\n","\t","  "),"",$res);
                DB::putcache("class_zerojudge_$user",$res,10);
            }
            $this->zjcore->html_sumary[$user] = $res;
        }
        return ;
    }
    
	function query($uid,$pid)
	{
	    global $_E;
	    $pid = $this->zjcore->reg_problemid($pid);
	    return $this->zjcore->query($uid,$pid);
	}
	
	function showname($pid){
	    $pname = $this->zjcore->reg_problemid($pid);
	    return "<a href='http://zerojudge.tw/ShowProblem?problemid=$pname' target='_blank'>ZJ $pname</a>";
	}
	
	function challink($uid,$pid,$vid){
		$pname = $this->zjcore->reg_problemid($pid);
		if($vid=="NO")return "●";
		else return "<a href='http://zerojudge.tw/Submissions?problemid=".$pname."&account=".$uid."' target='_blank'>●</a>";
	}
}