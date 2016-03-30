<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_poi{
    public $version = '1.0';
    public $name = 'POI capturer';
	public $description = 'Polish Olympiad in Informatics capturer';
	public $copyright = 'by xiplus';
	public $pattern = "/^poi:[a-z]+\d+[a-z]+$/";
	private $zjcore;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://main.edu.pl/";
	    $this->zjcore->classname  = "class_poi";
	    $this->zjcore->userpage = "en/user.phtml?op=zgloszenia";
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_poi','Polish Olympiad in Informatics','Account Name',1)");
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
	    return 0;
	}
	
	function showname($pid){
		preg_match("/poi:([a-z]+)(\d+)([a-z]+)/", $pid, $match);
	    return "<a href='http://main.edu.pl/en/archive/".$match[1]."/".$match[2]."/".$match[3]."' target='_blank'>POI ".$match[3]."</a>";
	}
	
	function challink($uid,$pid){
		return "";
	}
}