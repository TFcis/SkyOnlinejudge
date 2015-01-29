<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_poj{
    public $version = '1.0';
    public $name = 'POJ capturer';
	public $description = 'PKU Online Judge capturer';
	public $copyright = 'by domen111';
	public $pattern = "/^poj[0-9]+$/i";
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://poj.org/";
	    $this->zjcore->userpage   = "userstatus?user_id=";
	    $this->zjcore->classname  = "class_poj";
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_poj','北京大學POJ','Account Name',1)");
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
	    $start=strpos($response,'document.write("<a href=problem?id="+id+">"+id+" </a>")')+57;
	    $end  =strpos($response,"</script></td></tr>");
	    $html =substr($response,$start,$end-$start);
		
	    $POJ_stats = 0;
	    if(strpos($html,$pid)!==false){
			$POJ_stats = 90;
		} else {
			$POJ_stats = 0;
		}
		return $POJ_stats;
	}
	
	function showname($pid){
	    $pid = preg_replace('/[^0-9]+/','',$pid);
	    return "<a href='http://poj.org/problem?id=$pid' target='_blank'>POJ $pid</a>";
	}
	
	function challink($uid,$pid){
		$pid = preg_replace('/[^0-9]+/','',$pid);
	    return "http://poj.org/status?problem_id=$pname&user_id=$uid&result=&language=";
	}
}