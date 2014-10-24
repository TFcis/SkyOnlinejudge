<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

class class_zerojudge{
    public $version = '1.0';
    public $name = 'ZJ capturer';
	public $description = 'Zerojudge capturer';
	public $copyright = 'by ECHO_STATS';
	public $pattern = "/^zj:[a-z]{1}[0-9]+$/";
	private $api = 'http://210.70.137.215/oj/be/api';
	private $useraclist = array();

	function install()
	{
	    $tb = DB::tname('ojlist');
	    DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_zerojudge','Zerojudge','Account Name',1)");
	    //set SQL
	}
	
	function checkid($id)
	{
	    return preg_match('/^[0-9a-zA-Z]+$/',$id);
	}

	function query($uid,$pid)
	{
	    //zja980
	    global $_E;
	    $pid = preg_replace('/^zj:/','',$pid);
	    $ZJ_stats = 0;
	    if( $response  = DB::loadcache("class_zerojudge_$uid") )
	    {
	        //EMPTY
	        $_E['template']['dbg'].="$uid load form cache<br>";
	    }
	    else
	    {
	        $response=@file_get_contents("http://zerojudge.tw/UserStatistic?account=".$uid);
	        if($response)
	        {
    	        $_E['template']['dbg'].="$uid download from ZJ<br>";
    	        DB::putcache("class_zerojudge_$uid",$response,5);
	        }
	    }
		
		if(!$response) return 0;
		if(!(strrpos($response,"DataException")===false)) return 0;

		$start=strpos($response,"?problemid=".$pid);
		$end  =strpos($response,">".$pid."</a>");
		$html =substr($response,$start,$end-$start);
		//print '<td>';
		
		if(strpos($html,'class="acstyle"')){
			$ZJ_stats = 9;
		} else if(strpos($html,'color: #666666; font-weight: bold;')){
			$ZJ_stats = 0;
		} else if(strpos($html,'color: #666666')) {
			$ZJ_stats = 0;
		} else {
			//THROW ERROR
		}


		return $ZJ_stats;
	}
	
}