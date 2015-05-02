<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_hoj{
    public $version = '1.0';
    public $name = 'HOJ capturer';
	public $description = 'HSNU Online Judge capturer';
	public $copyright = 'by xiplus';
	public $pattern = "/^hoj[0-9]+$/i";
    private $cookiefile;
    private $loginflag = false ;
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://hoj.twbbs.org/judge/";
	    $this->zjcore->userpage   = "user/view/";
	    $this->zjcore->classname  = "class_hoj";
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
	            (NULL,'class_hoj','師大附中HOJ','HOJ uid',1)");
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
            $this->httpRequest('http://hoj.twbbs.org/judge/user/login',array(
                'username' => 'SkyOJ-BOT', 
                'password'  => '1234')
            );
            $this->loginflag = true;
          
        }
		foreach($userlist as $user)
        {
            if( !$this->checkid($user) ){
	            continue;
            }  
            if( $res = DB::loadcache("class_hoj_$user") && false )
            {
                //.....
            }
            else
            {
                $res = $this->httpRequest("http://hoj.twbbs.org/judge/user/view/".$user,false,false);
                $res = str_replace(array("\r\n","\t","  "),"",$res);
                DB::putcache("class_hoj_$user",$res,10);
            }
            $this->zjcore->html_sumary[$user] = $res;
        }
        $this->html = $this->zjcore->html_sumary;
        return ;
    }
    
	function query($uid,$pid)
	{
	    global $_E;
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    $response = $this->html[$uid];
	    if(!$response)return 0;
		$start=strpos($response,'hoj.twbbs.org/judge/problem/view/'.$pid.'"');
		$end  =strpos($response,">".$pid."</");
	    $html =substr($response,$start,$end-$start);
		
	    $HOJ_stats = 0;
	    if(strpos($html,"blue")!==false){
			$HOJ_stats = 90;
		} else if(strpos($html,"red")!==false){
			$HOJ_stats = 70;
		} else {
			$HOJ_stats = 0;
		}
		return $HOJ_stats;
	}
	
	function showname($pid){
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    return "<a href='http://hoj.twbbs.org/judge/problem/view/$pid' target='_blank'>HOJ $pid</a>";
	}
	
	function challink($uid,$pid){
		$pid = preg_replace('/[^0-9]+/','',$pid);
	    return "http://hoj.twbbs.org/judge/judge/status?prob=$pid";//&&user=$uid";
	}
}