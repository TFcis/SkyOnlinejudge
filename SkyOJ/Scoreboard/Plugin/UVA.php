<?php namespace SkyOJ\Scoreboard\Plugin;

class UVA extends \SkyOJ\Scoreboard\OJCapture
{
    const VERSION = '1.0';
    const NAME = 'UVA Capture';
    const DESCRIPTION = 'UVA Capture';
    const COPYRIGHT = 'LFsWang';
    
    private $uvauid = null;
    private $uvapid = null;
    private $cachechange = false;
    private $submissions = [];
    /*
    submissions struct:
    $submissions[$uvauid][$uvapid] = $subs;
    $subs = [$sub,...];
    $subs is array
    $sub is object
    $sub["sid"] = SubmissionIDofUVA;
    $sub["pid"] = ProblemIDofUVA;
    $sub["time"] = SubmissionTime UnixTimeStamp
    $sub["verdict"] = \SKYOJ\RESULTCODE
    */

    private function loadcache()
    {
        global $SkyOJ; 
        $this->uvauid = $SkyOJ->cache_pool->get('OJCapture_UVA_UVA_username2uid',[]);
        $this->uvapid = $SkyOJ->cache_pool->get('OJCapture_UVA_UVA_pnum2pid',[]);
    }

    private function setcache()
    {
        global $SkyOJ;
        $this->uvauid = $this->uvauid??[];
        $this->uvapid = $this->uvapid??[];

        foreach( $this->uvauid as $acct => $uid )
        {
            if( $uid === 0 )
                unset($this->uvauid[$acct]);
        }

        foreach($this->submissions as $user => $sub)
        {
            $SkyOJ->cache_pool->set("OJCapture_UVA_UVA_submission_$user",$sub,time()+8640000);
        }

        $SkyOJ->cache_pool->set('OJCapture_UVA_UVA_username2uid',$this->uvauid,time()+8640000);
        $SkyOJ->cache_pool->set('OJCapture_UVA_UVA_pnum2pid',$this->uvapid,time()+8640000);
    }

    private function loadSubmissionCache($uid)
    {
        global $SkyOJ; 
        $this->submissions[$uid] = $SkyOJ->cache_pool->get("OJCapture_UVA_UVA_submission_$uid",[]);
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

    function problink($pname):string
    {
        $pid = self::matched_name_to_pnum($pname);
        return "http://domen111.github.io/UVa-Easy-Viewer/?$pid";
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

        $this->cachechange = true;
        $this->uvauid[$uname] =(int) $uid;

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

    private function getSubmissionsFromUVA($uids, $pids)
    {
        $uidstr = implode(',',$uids);
        $pidstr = implode(',',$pids);
        $response = @file_get_contents("https://uhunt.onlinejudge.org/api/subs-pids/$uidstr/$pidstr/0");
        //echo "https://uhunt.onlinejudge.org/api/subs-pids/$uidstr/$pidstr/0";
        $json = json_decode($response);
        if(!$json)
            return [];
        $submissions = [];
        foreach($uids as $uid){
            $sublist = $json->$uid->subs;
            foreach($sublist as $sub)
            {
                $sub_save["sid"] = $sub[0];
                $sub_save["pid"] = $sub[1];
                $sub_save["time"] = $sub[4];
                $verdict = \SKYOJ\RESULTCODE::WAIT;
                switch($sub[2])
                {
                    case 10 : $verdict=\SKYOJ\RESULTCODE::JE;break;
                    case 15 : $verdict=\SKYOJ\RESULTCODE::JE;break;
                    case 20 : $verdict=\SKYOJ\RESULTCODE::JUDGING;break;
                    case 30 : $verdict=\SKYOJ\RESULTCODE::CE;break;
                    case 35 : $verdict=\SKYOJ\RESULTCODE::RF;break;
                    case 40 : $verdict=\SKYOJ\RESULTCODE::RE;break;
                    case 45 : $verdict=\SKYOJ\RESULTCODE::OLE;break;
                    case 50 : $verdict=\SKYOJ\RESULTCODE::TLE;break;
                    case 60 : $verdict=\SKYOJ\RESULTCODE::MLE;break;
                    case 70 : $verdict=\SKYOJ\RESULTCODE::WA;break;
                    case 80 : $verdict=\SKYOJ\RESULTCODE::PE;break;
                    case 90 : $verdict=\SKYOJ\RESULTCODE::AC;break;
                }
                $sub_save["verdict"] = $verdict;
                $submissions[$uid][$sub_save["pid"]][] = $sub_save;
            }
        }
        //echo json_encode($submissions);
        return $submissions;
    }

    private function getSubmission($uid, $pid)
    {
        if(isset($this->submissions[$uid][$pid]))
            return $this->submissions[$uid][$pid];
        return null;
    }

    public function verifyAccount(string $acct):bool
    {
        if( !isset($this->uvauid) )
            $this->loadcache();
        return $this->username2uid($acct) !== 0;
    }

    public function rebuild($uids, $problems)
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
        $subs = $this->getSubmissionsFromUVA($uvauid,$uvapid);
        //echo json_encode($subs);
        foreach($subs as $user => $sub){
            foreach($sub as $pro => $sublist){
                $this->submissions[$user][$pro] = $sublist;
            }
        }
        $this->cachechange = true;
    }

    public function prepare($uids, $problems)
    {
        foreach($uids as $uid)
        {
            $acct = $this->uid2ojaccount($uid);
            if( isset($acct) )
            {
                $uvauid = $this->username2uid($acct);
                if( $uvauid !== 0 )
                {
                    if( !isset($this->submissions[$uvauid]) )
                    {
                        $this->loadSubmissionCache($uvauid);
                    }
                }
            }
        }
        //echo json_encode($this->submissions);
    }

    public function query($uid, $problem, $start=null, $end=null):array
    {
        $acct = $this->uid2ojaccount($uid);
        if( !isset($acct) ) return [0,0];
        $uid = $this->username2uid($acct);
        $pid = $this->pnum2pid($this->matched_name_to_pnum($problem));

        if( $uid===0||$pid===0 ) return false;

        $subs = $this->getSubmission($uid,$pid);
        //echo json_encode($subs);
        if($subs==null)return [0,0];
        $verdict = 100;
        foreach($subs as $sub)
        {
            if(($sub["time"]<$start || $sub["time"]>$end) && !($start==null || $end==null))
                continue;
            $verdict = min($verdict,$sub["verdict"]);
        }
        if($verdict==100)$verdict = 0;
        if($verdict==\SKYOJ\RESULTCODE::AC)return [$verdict,100];
        return [$verdict,0];
    }
}

