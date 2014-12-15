<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_tioj{
    public $version = '1.0';
    public $name = 'TIOJ capturer';
	public $description = 'TIOJ capturer';
	public $copyright = 'by ECHO_STATS';
	public $pattern = "/^tioj[0-9]+$/";
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://tioj.ck.tp.edu.tw/";
	    $this->zjcore->userpage   = "users/";
	    $this->zjcore->classname  = "class_tioj";
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_tioj','建中TIOJ','Account Name',1)");
	}
	
	function checkid($id)
	{
	    return $this->zjcore->checkid($id);
	}
	
    function preprocess($userlist,$problems)
    {
        global $_E;
        $this->zjcore->preprocess($userlist,$problems);
        $this->html = $this->zjcore->html_sumary;
        return ;
    }
    
	function query($uid,$pid)
	{
	    global $_E;
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    $response = $this->html[$uid];
	    if(!$response)return 0;
	    $start=strpos($response,"/problems/".$pid."/submissions")-25;
	    $end  =strpos($response,"/problems/".$pid."/submissions")-6;
	    $html =substr($response,$start,$end-$start);
	    
	    $TIOJ_stats = 0;
	    if(strpos($html,'text-success')){
			$TIOJ_stats = 90;
		} else if(strpos($html,'text-warning')){
			$TIOJ_stats = 70;
		} else if(strpos($html,'text-muted')) {
			$TIOJ_stats = 0;
		} else {
			//THROW ERROR
		}
		return $TIOJ_stats;
	}
	
	function showname($pid){
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    return "<a href='http://tioj.ck.tp.edu.tw/problems/$pid' target='_blank'>TIOJ $pid</a>";
	}
	
	function challink($uid,$pid){
		$pid = preg_replace('/[^0-9]+/','',$pid);
	    return "http://tioj.ck.tp.edu.tw/problems/".$pname."/submissions?filter_username=".$uid;
	}
}