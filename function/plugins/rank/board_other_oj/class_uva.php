<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class class_uva{
    public $version = '1.0';
    public $name = 'UVa capturer';
	public $description = 'UVa capturer';
	public $copyright = 'test by Domen';
	public $pattern = "/^uva[0-9]+$/i";
	private $useraclist = array();
	

	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `tojtest_ojlist`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_uva','UVa Online Judge','UVa user name',1)");
	    //set SQL
	}
	
	function checkid($uname)
	{
	    $uname = (string)$uname;
	    if(!preg_match('/[\da-zA-Z]{2,}/',$uname)) //No spaces, at least 2 characters and contain 0-9,a-z,A-Z
	    	return false;
	    if(!$this->uname2id($uname))
	    	return false;
	    return true;
	}
	
	function realpname($pname)
	{
	    return preg_replace('/[^0-9]*/','',$pname);
	}
	function uname2id($uname){
		$data = DB::loadcache("class_uva_uname2id_$uname");
		if($data === false){
			$data = file_get_contents("http://uhunt.felix-halim.net/api/uname2uid/$uname");
			if($data=="0")
				return false;
			DB::putcache("class_uva_uname2id_$uname", trim($data), 'forever'); //todo forever
		}
		return $data;
	}
	
	function probId2Num($pid){
	    $pid = $this->realpname($pid);
		$data = DB::loadcache("class_uva_probId2Num_$pid");
		if($data === false){
			$data = file_get_contents("http://uhunt.felix-halim.net/api/p/id/$pid");
			$data = json_decode($data, true);
			$data = trim($data["num"]);
			DB::putcache("class_uva_probId2Num_$pid", $data, 'forever'); //todo forever
		}
		return $data;
	}
	
	function preprocess($userlist,$problist)
	{
		//load cache
		$tul = array();
		$tpl = array();
		foreach($userlist as $user){
			foreach($problist as $pnum){
				if(DB::loadcache("class_uva_uid_$user"."_pnum_$pnum") === false){
					$tul[$user] = true;
					$tpl[$pnum] = true;
				}
			}
		}
		$userlist = array();
		$problist = array();
		foreach($tul as $user => $t)
			array_push($userlist,$this->uname2id($user));
		foreach($tpl as $pnum => $t)
			array_push($problist,$this->realpname($pnum));
		
		//fetch
		$data = file_get_contents("http://uhunt.felix-halim.net/api/subs-nums/".implode(',', $userlist)."/".implode(',', $problist)."/0");
		if($data === false) return;
		$data = json_decode($data, true);
		foreach($userlist as $user){
			$uid = $user;
			$udata = $data[$uid]['subs'];
			$verdict = array();
			foreach($udata as $sub){
				if($sub[2] != 20)
				{
				    $pnum = $this->probId2Num($sub[1]);
				    if(!isset($verdict[$pnum])) 
				        $verdict[$pnum] = 0;
					$verdict[$pnum] = max($verdict[$pnum], $sub[2]);
				}
			}
			foreach($verdict as $p => $v){
				DB::putcache("class_uva_uid_$uid"."_pnum_$p", $v, 86400);
			}
		}
	}
	
	function query($uid,$pnum)
	{
	    $pnum = preg_replace('/[^0-9]*/','',$pnum);
	    $uid  = $this->uname2id($uid);
	    $pnum = $this->realpname($pnum);
	    $cache = DB::loadcache("class_uva_uid_$uid"."_pnum_$pnum");
	    if($cache===false)
	    	return 0; //throw error
	    if($cache=="90")
	        return 90;
	    else
	        return 70;
	}
	
	function showname($str)
	{
	    $pnum = preg_replace('/[^0-9]*/','',$str);
		$url =  "http://domen.heliohost.org/uva/?$pnum";
	    $str="<a style='color:rgb(255,246,157)' href='$url' target='_blank'>UVa $pnum</a>";
	    return $str;
	}
}