<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_bzoj{
    public $version = '1.0';
    public $name = 'BZOJ capturer';
	public $description = '大视野在线测评 capturer';
	public $copyright = 'by xiplus';
	public $pattern = "/^bzoj[0-9]+$/i";
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://www.lydsy.com/JudgeOnline/";
	    $this->zjcore->userpage   = "userinfo.php?user=";
	    $this->zjcore->classname  = "class_bzoj";
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_bzoj','BZOJ','Account Name',1)");
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
	    $start=strpos($response,'document.write("<a href=problem.php?id="+id+">"+id+" </a>")')+61;
	    $end  =strpos($response,"</script></tr>");
	    $html =substr($response,$start,$end-$start);
		
	    $BZOJ_stats = 0;
	    if(strpos($html,$pid)!==false){
			$BZOJ_stats = 90;
		} else {
			$BZOJ_stats = 0;
		}
		return $BZOJ_stats;
	}
	
	function showname($pid){
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    return "<a href='http://www.lydsy.com/JudgeOnline/problem.php?id=$pid' target='_blank'>BZOJ $pid</a>";
	}
	
	function challink($uid,$pid){
		$pid = preg_replace('/[^0-9]+/','',$pid);
	    return "http://www.lydsy.com/JudgeOnline/status.php?problem_id=$pid&user_id=$uid";
	}
}