<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_peg{
    public $version = '1.0';
    public $name = 'PEG capturer';
	public $description = 'PEG Online Judge capturer';
	public $copyright = 'by xiplus';
	public $pattern = "/^peg:[A-Za-z0-9]+$/";
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://wcipeg.com/";
	    $this->zjcore->userpage   = "user/";
	    $this->zjcore->classname  = "class_peg";
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_peg','PEG Online Judge','Account Name',1)");
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
	    preg_match('/PEG:([A-Za-z0-9]+)/i',$pid,$match);
		$pid=$match[1];
	    $response = $this->html[$uid];
	    if(!$response)return 0;
	    $html = str_replace("\n", "", $response);

	    $stats = 0;
	    if (preg_match("/<li><a href=\"http:\/\/wcipeg\.com\/problem\/$pid\">.+?<\/a>&nbsp;\(<a href=\"http:\/\/wcipeg\.com\/submissions\/$uid,$pid\">(\d+\.?\d*)&nbsp;\/&nbsp;(\d+\.?\d*)<\/a>\)<\/li>/", $html, $match)) {
	    	if ($match[1] == $match[2]) {
	    		$stats = 90;
	    	} else {
	    		$stats = 70;
	    	}
	    }
		return $stats;
	}
	
	function showname($pid){
	    preg_match('/PEG:([A-Za-z0-9]+)/i',$pid,$match);
		$pid=$match[1];
	    return "<a href='http://wcipeg.com/problem/$pid' target='_blank'>PEG $pid</a>";
	}
	
	function challink($uid,$pid){
	    preg_match('/PEG:([A-Za-z0-9]+)/i',$pid,$match);
		$pid=$match[1];
	    return "http://wcipeg.com/submissions/".$uid.",".$pid;
	}
}