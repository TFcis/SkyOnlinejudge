<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class ScoreBlock{
    public $try_times;
    public $ac_time;
    public $is_ac;
    public $firstblood;
};

class UserBlock
{
    public $uid;
    public $total_submit;
    public $ac;
    public $ac_time;
    static function acm_cmp($a,$b){
        if( $a->ac!=$b->ac ) return $b->ac<=>$a->ac;
        if( $a->ac_time!=$b->ac_time ) return $b->ac_time<=>$a->ac_time;
        return $b->total_submit<=>$a->total_submit;
    }
    
}

function sub_scoreboardHandle(\SKYOJ\Contest $contest)
{
    global $SkyOJ,$_E,$_G;
    $tname = \DB::tname('challenge');
    $tuid  = \DB::tname('contest_user');
    $tpid  = \DB::tname('contest_problem');

    $fzend = \SKYOJ\get_timestamp( max([ strtotime($contest->endtime)-$contest->freeze_sec,strtotime($contest->starttime) ]) );
    $all = \DB::fetchAllEx("SELECT `pid`,`uid`,`result`,`timestamp` FROM $tname 
        WHERE  `timestamp` BETWEEN ? AND ? 
            AND `uid` IN (SELECT `uid` FROM $tuid WHERE `cont_id`=?) 
            AND `pid` IN (SELECT `uid` FROM $tuid WHERE `cont_id`=?) 
        ORDER BY `cid` ASC",
        $contest->starttime,$fzend,$contest->cont_id(),$contest->cont_id()
    );
    $uids = \DB::fetchAllEx("SELECT `uid` FROM $tuid WHERE `cont_id`=?",$contest->cont_id());
    $pids  = $contest->get_all_problems_info();

    $scoreboard=[];//[uid][pid]
    $userinfo  =[];
    foreach($uids as &$uid)
    {
        $uid=$uid['uid'];
        $userinfo[$uid] = new UserBlock();
        $userinfo[$uid]->uid=$uid;
        $userinfo[$uid]->total_submit=0;
        $userinfo[$uid]->ac=0;
        $userinfo[$uid]->ac_time=0;

        $scoreboard[$uid]=[];
        foreach($pids as $row)
        {
            $pid=$row->pid;
            $scoreboard[$uid][$pid]=new ScoreBlock();
            $scoreboard[$uid][$pid]->try_times = 0;
            $scoreboard[$uid][$pid]->is_ac     = 0;
            $scoreboard[$uid][$pid]->ac_time   = 0;
            $scoreboard[$uid][$pid]->firstblood= 0;
        }
    }
    unset($uid,$pid);

    $acset = [];
    foreach( $all as $row )
    {
        $uid=$row['uid'];
        $pid=$row['pid'];
        $verdict=$row['result'];
        $time=strtotime($row['timestamp'])-strtotime($contest->starttime);
        if( $scoreboard[$uid][$pid]->is_ac != 0 )continue;

        $scoreboard[$uid][$pid]->try_times++;
        if( $verdict == \SKYOJ\RESULTCODE::AC )
        {
            $scoreboard[$uid][$pid]->is_ac = 1;
            $scoreboard[$uid][$pid]->ac_time = (int)floor(($time + ($scoreboard[$uid][$pid]->try_times-1)*$contest->penalty)/60);
            if( !isset($acset[$pid]) )
            {
                $acset[$pid] = 1;
                $scoreboard[$uid][$pid]->firstblood = 1;
            }
            $userinfo[$uid]->total_submit+=$scoreboard[$uid][$pid]->try_times;
            $userinfo[$uid]->ac_time+=$scoreboard[$uid][$pid]->ac_time;
            $userinfo[$uid]->ac++;
        }
    }
    usort($userinfo,__NAMESPACE__."\\UserBlock::acm_cmp");

    $_E['template']['user'] = $userinfo;
    $_E['template']['pids'] = $pids;
    $_E['template']['scoreboard'] = $scoreboard;
    \Render::renderSingleTemplate('view_scoreboard_acm','contest');
    exit(0);
}