<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

class zjcore{
    public $websiteurl;
    public $classname;
    public $userpage = "UserStatistic?account=";
    public $html_sumary = array();

	function checkid($id)
	{
	    return preg_match('/^[0-9a-zA-Z]+$/',$id);
	}

    function preprocess($userlist,$problems)
    {
        global $_E;
        foreach($userlist as $user)
        {
            if( !$this->checkid($user) ){
	            continue;
            }
            if( $res = DB::loadcache($this->classname."_$user"))
            {
                //.....
            }
            else
            {
                $res = @file_get_contents($this->websiteurl.$this->userpage.$user);
                $res = str_replace(array("\r\n","\t","  "),"",$res);
                DB::putcache($this->classname."_$user",$res,10);
            }
            
            $this->html_sumary[$user] = $res;
        }
    }
    //pid real!
	function query($uid,$pid)
	{
	    global $_E;

	    $ZJ_stats = 0;

		$response = $this->html_sumary[$uid];
		if(!$response) return 0;
		if(!(strrpos($response,"DataException")===false)) return 0;

		$start=strpos($response,"?problemid=".$pid);
		$end  =strpos($response,">".$pid."</a>");
		$html =substr($response,$start,$end-$start);

		if(strpos($html,'"acstyle"')){
			$ZJ_stats = 90;
		} else if(strpos($html,'color: #666666; font-weight: bold;')){
			$ZJ_stats = 70;
		} else if(strpos($html,'color: #666666')) {
			$ZJ_stats = 0;
		} else {
			//THROW ERROR
		}

		return $ZJ_stats;
	}
	
	function reg_problemid($pid)
	{
	    if(!preg_match('/.*:([a-zA-Z]{1})(\d+)/',$pid,$match))
	        return false;
	    $word = $match[1];
	    $num = $match[2];
	    $word = strtolower($word);
	    $num  = str_pad($num,3,"0",STR_PAD_LEFT);
	    return $word.$num;
	}
}