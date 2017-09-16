<?php namespace SkyOJ\Scoreboard\Plugin;

class UVA extends \SkyOJ\Plugin\Scoreboard
{
    private $uvauid = null;
    private $uvapid = null;
    private $cachechange = false;

    private function loadcache()
    {
        global $SkyOJ; 
        $this->uvauid = $SkyOJ->cache_pool->get('UVA_username2uid',[]);
        $this->uvapid = $SkyOJ->cache_pool->get('UVA_pnum2pid',[]);
    }

    private function setcache()
    {
        global $SkyOJ; 
        $SkyOJ->cache_pool->set('UVA_username2uid',$this->uvauid??[],time()+8640000);
        $SkyOJ->cache_pool->set('UVA_pnum2pid',$this->uvapid??[],time()+8640000);
    }

    function __destruct()
    {
        if( $this->cachechange )
            $this->setcache();
    }
    function patten():string
    {
        return "/^uva[0-9]+$/i";
    }
    function is_match(string $name):bool
    {
        return preg_match($this->patten(),$name)===1;
    }

    function get_title(string $name):?string
    {
        return $name;
    }

    private function matched_name_to_pnum($name)
    {
        return substr($name,3);
    }
    
    
    private function username2uid(string $uname):int
    {
        if( !isset($this->uvauid) )
            $this->loadcache();

        if( array_key_exists($uname,$this->uvauid) )
            return $this->uvauid[$uname];

        $response = trim(@file_get_contents("https://uhunt.onlinejudge.org/api/uname2uid/".$uname));

        if( !\SKYOJ\check_tocint($response) )
            return 0;

        $uid = (int)$response;
        if( $uid!==0 )
        {
            $this->cachechange = true;
            $this->uvauid[$uname] =(int) $uid;
        }
        return $uid;
    }

    private function pnum2pid(string $pnum):int
    {
        if( !isset($this->uvapid) )
            $this->loadcache();
        
        if( array_key_exists($pnum,$this->uvapid) )
            return $this->uvapid[$pnum];

        $response = @file_get_contents("https://uhunt.onlinejudge.org/api/p/num/".$pnum);
        $json = json_decode($response,true);

        if( $json!==false && array_key_exists('pid',$json) )
        {
            $this->cachechange = true;
            return $this->uvapid[$pnum] = (int)$json['pid'];
        }
        return 0;
    }

    private function fetchACBitMap(array $uids)
    {
        if( PHP_INT_MAX == 2147483647 )
            \SkyOJ\Code\Excption('32 bit version can not handle fetchACBitMap!');
        
        $querystr = implode(',',$uids);
        $response = @file_get_contents("https://uhunt.onlinejudge.org/api/solved-bits/".$querystr);
        $json = json_decode($response);
        if( $json === false ) return [];
        return $json;
    }

    private $acmap = [];
    public function prepare($uids,$problems)
    {
        $uvauid = [];
        $uvapid = [];

        foreach($uids as $uid)
        {
            $acct = $this->uid2ojaccount($uid);
            if( isset($acct) )
            {
                if( $this->username2uid($acct) !== 0 )
                    $uvauid[] = $this->username2uid($acct);
            }
        }

        foreach($problems as $prob)
        {
            $pnum = $this->matched_name_to_pnum($prob);
            $pid = $this->pnum2pid($pnum);
            if( $pid !== 0 )
            {
                $uvapid[] = $pid;
            }
        }

        $acdata = $this->fetchACBitMap($uvauid);
        foreach( $acdata as $row )
        {
            $this->acmap[$row->uid] = $row->solved;
        }
    }

    public function query($uid,$problem)
    {
        $acct = $this->uid2ojaccount($uid);
        if( !isset($acct) ) return [0,0];
        $uid = $this->username2uid($acct);
        $pid = $this->pnum2pid($this->matched_name_to_pnum($problem));

        if( $uid===0||$pid===0 ) return false;

        $pos = intdiv($pid,32);
        $bit = $pid % 32;
        $isAC = ($this->acmap[$uid][$pos] >> $bit) & 1;

        if( $isAC ) return [\SKYOJ\RESULTCODE::AC,100];
        return [0,0];
    }
}