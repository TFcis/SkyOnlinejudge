<?php namespace SkyOJ\Scoreboard\Plugin;

class TOJ extends \SkyOJ\Scoreboard\OJCapture
{
    const VERSION = '1.0';
    const NAME = 'TOJ Capture';
    const DESCRIPTION = 'TOJ Capture';
    const COPYRIGHT = 'lys0829';
    
    private $tojuid = null;
    private $cachechange = false;

    private function loadcache()
    {
        global $SkyOJ; 
        $this->tojuid = $SkyOJ->cache_pool->get('TOJ_username2uid',[]);
    }

    private function setcache()
    {
        global $SkyOJ;
        $this->tojuid = $this->tojuid??[];

        foreach( $this->uvauid as $acct => $uid )
        {
            if( $uid === 0 )
                unset($this->uvauid[$acct]);
        }

        $SkyOJ->cache_pool->set('UVA_username2uid',$this->uvauid,time()+8640000);
        $SkyOJ->cache_pool->set('UVA_pnum2pid',$this->uvapid,time()+8640000);
    }

    function __destruct()
    {
        if( $this->cachechange )
            $this->setcache();
    }

    function patten():string
    {
        return "/^toj[0-9]+$/i";
    }

    public function get($page,$data)
    {
        $context['http'] =  [
            'timeout'   => 60,
            'method'    => 'GET',
            'content'   => http_build_query($data, '', '&'),
        ];
        $response = @file_get_contents('http://210.70.137.215/oj/be/'.$page, false, stream_context_create($context));

        return $response;
    }

    function is_match(string $name):bool
    {
        return preg_match($this->patten(),$name)===1;
    }

    function get_title(string $name):?string
    {
        return $name;
    }

    function problink($pname):string
    {
        $pid = self::matched_name_to_pnum($pname);
        return "http://toj.tfcis.org/oj/pro/$pid/";
    }

    function challink($uid, $pname):string
    {
        $pid = self::matched_name_to_pnum($pname);
        $acct = $this->uid2ojaccount($uid);
        return 'http://toj.tfcis.org/oj/chal/?proid='.$pid.'&acctid='.$uid;
    }

    private function matched_name_to_pnum($name)
    {
        return preg_replace('/[^0-9]*/', '', $name);
    }

    public function verifyAccount(string $acct):bool
    {
        if(\SKYOJ\check_tocint($acct))
            return true;
        return false;
    }

    private function getSubmissions($uid,$pid)
    {
        $req = [];
        $req['proid'] = $pid;
        $req['acctid'] = $uid;
        $res = $this->get('chal',$req);
        $res = str_replace(array("\r\n","\t","  "),"",$res);
    }

    public function rebuild($uids,$problems)
    {
        
    }

    private $acmap = [];
    public function prepare($uids,$problems)
    {
        
    }

    public function query($uid,$problem,$start=null,$end=null):array
    {
        return [0,0];
    }
}

