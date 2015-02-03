<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}
require_once('class_zjcore.inc.php');
class class_cf{
    public $version = '1.0';
    public $name = 'CF capturer';
	public $description = 'Codeforces capturer';
	public $copyright = 'by xiplus';
	public $pattern = "/^cf:[0-9]+[A-Z]{1}$/i";
	private $zjcore;
	private $html;

	function __construct()
	{
	    $this->zjcore = new zjcore;
	    $this->zjcore->websiteurl = "http://codeforces.com/";
	    $this->zjcore->userpage   = "api/";
	    $this->zjcore->classname  = "class_cf";
		include("cf_api_key.php");
		$this->zjcore->userpagepattern ='$WEB.$USERPAGE.\'user.status?handle=\'.$user.\'&apiKey='.$key.'&time=\'.time().\'&apiSig=123456\'.hash(\'sha512\',\'123456/user.status?apiKey='.$key.'&handle=\'.$user.\'&time=\'.time().\'#'.$secret.'\')';
	}
	
	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_cf','Codeforces','Account Name',1)");
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
	    preg_match('/CF:([0-9]+[A-Z]{1})/i',$pid,$match);
		$pid=$match[1].$match[2];
	    $response = $this->html[$uid];
	    $result=json_decode($response,true);
		
		if($result["status"]!="OK")return 0;
		else {
			$result=$result["result"];
			foreach ($result as $temp){
				if($temp["problem"]["contestId"].$temp["problem"]["index"]==$pid){
					if($temp["verdict"]=="OK")return 90;
				}
			}
			return 70;
		}
	}
	
	function showname($pid){
	    preg_match('/CF:([0-9]+)([A-Z]{1})/i',$pid,$match);
		$pid=$match[1].$match[2];
	    return "<a href='http://codeforces.com/problemset/problem/".$match[1]."/".$match[2]."' target='_blank'>CF $pid</a>";
	}
	
	function challink($uid,$pid){
	    return "";
	}
}