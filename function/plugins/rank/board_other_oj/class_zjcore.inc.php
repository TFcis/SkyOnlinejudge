<?php
if(!defined('IN_SKYOJSYSTEM'))
{
    exit('Access denied');
}

class zjcore{
    public $websiteurl;
    public $classname;
    public $userpage = "UserStatistic?account=";
    public $useraclist = array();
    public $html_sumary = array();

	function checkid($id)
	{
	    return preg_match('/^[0-9a-zA-Z]+$/',$id);
	}

	function build($user){
        if( DB::loadcache($this->classname."_work_$user") ){
            return ;
        }
        if( function_exists('pcntl_fork') )
            $pid = pcntl_fork();
        else
            $pid = 'NO_PCNTL';
        if ( $pid === -1 ){
            $pid = 'NO_PCNTL';
        }
        
        if( $pid!==0 && $pid!=='NO_PCNTL' ){
            return ;
        }
        
        DB::putcache($this->classname."_work_$user",'work',86400);
        $response = file_get_contents($this->websiteurl.$this->userpage.$user);
        if($response !== false ){
            DB::putcache($this->classname."_$user",
                array('time' => time()+600+rand(0,300),'data'=>$response)
                ,86400);
        }
        DB::deletecache($this->classname."_work_$user");
        
        if($pid === 'NO_PCNTL'){
            return ;
        }
        sleep(5);
        #Fix No output bug
        exit('<head><meta http-equiv="refresh" content="1"/></head>');
	}
	
    function preprocess($userlist,$problems)
    {
        global $_E;
        foreach($userlist as $user)
        {
            if( !$this->checkid($user) ){
	            continue;
            }
            $this->html_sumary[$user] = '';
            $data = DB::loadcache($this->classname."_$user");
            if($data){
                $_E['template']['dbg'].="$user load form cache<br>";
                $this->html_sumary[$user] = $data['data'];
            }
            if(!$data || $data['time']<time() ){
                $_E['template']['dbg'].="$user download from ".$this->classname."<br>";
                $this->build($user);
            }
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