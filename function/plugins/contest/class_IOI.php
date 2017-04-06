<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_IOI extends ContestManger
{
    const VERSION = '0.1-alpha';
    const NAME = 'IOI';
    const DESCRIPTION = 'IOI Style Contest';
    const COPYRIGHT = 'Sylveon';
    private $contest;

    public static function requiredFunctions():array
    {
        return [];
    }

    public static function licence_tmpl():array
    {
        return ['mit_license', 'user'];
    }

    public static function installForm():array
    {
        return [];
    }

    public static function install(&$msg):bool
    {
        return true;
    }

    public function compare(\SKYOJ\UserBlock $a,\SKYOJ\UserBlock $b)
    {
        return $b->score <=> $a->score;
    }

    public function scoreboard_template($resolver=false):array
    {
        global $_G;
        if(\userControl::isAdmin($_G['uid']) && $resolver){
            return ['view_scoreboard_resolver_ioi','contest','resolver'];
        }
        return ['view_scoreboard_ioi','contest'];
    }
    
    public function resolver_template():array
    {
        return ['bangkok_resolver_ioi','contest'];
    }
    
    public function get_scoreboard_by_timestamp(\SKYOJ\Contest $contest,$start,$end)
    {
        $all  = $contest->get_chal_data_by_timestamp($start,$end);
        $uids = $contest->get_all_users_info();
        $pids = $contest->get_all_problems_info();
        $scoreboard =[];
        $userinfo   =[];
        $probleminfo=[];
        $probleminfo_build = false;

        foreach($uids as $user)
        {
            $uid=$user->uid;
            if( !\SKYOJ\ContestTeamStateEnum::allow($user->state) )
            {
                continue;
            }
            $userinfo[$uid] = new \SKYOJ\UserBlock();
            $userinfo[$uid]->uid=$uid;
            $userinfo[$uid]->total_submit=0;
            $userinfo[$uid]->ac=0;
            $userinfo[$uid]->ac_time=0;
            $userinfo[$uid]->score=0;

            $scoreboard[$uid]=[];
            foreach($pids as $row)
            {
                $pid=$row->pid;
                $ptag=$row->ptag;
                $scoreboard[$uid][$pid]=new \SKYOJ\ScoreBlock();
                $scoreboard[$uid][$pid]->try_times = 0;
                $scoreboard[$uid][$pid]->is_ac     = 0;
                $scoreboard[$uid][$pid]->ac_time   = 0;
                $scoreboard[$uid][$pid]->firstblood= 0;
                $scoreboard[$uid][$pid]->score     = 0;
                if( !$probleminfo_build )
                {
                    $probleminfo[$pid] = new \SKYOJ\ProblemBlock();
                    $probleminfo[$pid]->pid = $pid;
                    $probleminfo[$pid]->ptag = $row->ptag;
                    $probleminfo[$pid]->try_times = 0;
                    $probleminfo[$pid]->ac_times  = 0;
                }
            }
            $probleminfo_build = true;
        }

        $acset = [];
        foreach( $all as $row )
        {
            $uid=$row['uid'];
            $pid=$row['pid'];
            $ptag='';
            foreach($pids as $p){
                if($p->pid==$row['pid']){
                    $ptag=$p->ptag;
                    break;
                }
            }
            $verdict=$row['result'];
            $time=strtotime($row['timestamp'])-strtotime($contest->starttime);
            if( $scoreboard[$uid][$pid]->is_ac != 0 )continue;

            $scoreboard[$uid][$pid]->try_times++;
            $probleminfo[$pid]->try_times++;
            if( $row['score'] > $scoreboard[$uid][$pid]->score )
            {
                $delta = $row['score'] - $scoreboard[$uid][$pid]->score; 
                $scoreboard[$uid][$pid]->score = $row['score'];
                $userinfo[$uid]->score += $delta;
            }
            if( $verdict == \SKYOJ\RESULTCODE::AC )
            {
                $scoreboard[$uid][$pid]->is_ac = 1;
                $scoreboard[$uid][$pid]->ac_time = (int)floor($time/60); 
                if( !isset($acset[$pid]) )
                {
                    $acset[$pid] = 1;
                    $scoreboard[$uid][$pid]->firstblood = 1;
                }
                $userinfo[$uid]->total_submit+=$scoreboard[$uid][$pid]->try_times;
                $userinfo[$uid]->ac_time+=(int)floor(($time + ($scoreboard[$uid][$pid]->try_times-1)*$contest->penalty)/60);
                $userinfo[$uid]->ac++;
                $probleminfo[$pid]->ac_times++;
            }
        }
        
        usort($userinfo,[$contest,'rank_cmp']);
        return  ['scoreboard'=>$scoreboard,'userinfo'=>$userinfo,'probleminfo'=>$probleminfo];
    }
    
    public function to_resolver_json(\SKYOJ\Contest $contest,$scordboard_data)
    {
        //solved attempted
        $json = [];
        $json["solved"] = [];
        $json["attempted"] = [];
        foreach($scordboard_data['probleminfo'] as $prob)
        {
            $json["solved"][$prob->ptag] = $prob->ac_times;
            $json["attempted"][$prob->ptag] = $prob->try_times;
            $json["problems"][$prob->ptag] = [];
            $json["problems"][$prob->ptag]["score"] = 100;
        }
        $rank = 1;
        $last = null;
        $json["scoreboard"] = [];
        foreach($scordboard_data['userinfo'] as $user)
        {
            if( isset($last)&&$contest->rank_cmp($last,$user)!=0 ){
                $rank++;
            }
            $last = $user;
            $d = [];
            $d['id'] = (int)$user->uid;
            $d['rank'] = $rank;
            $d['solved'] = (int)$user->ac;
            $d['points'] = (int)$user->ac_time;

            $nickname=\SKYOJ\nickname($user->uid);
            $d['name'] = $nickname[$user->uid];
            $d['group'] = '';

            foreach($scordboard_data['probleminfo'] as $prob)
            {
                $sb=$scordboard_data['scoreboard'][$user->uid][$prob->pid];
                $d[$prob->ptag] = [];
                $d[$prob->ptag]['a'] = $sb->try_times;
                $d[$prob->ptag]['t'] = $sb->score;
                if( $sb->firstblood )$d[$prob->ptag]['s'] = "first";
                else if( $sb->is_ac )$d[$prob->ptag]['s'] = "solved";
                else if( $sb->try_times ) $d[$prob->ptag]['s'] = "tried";
                else $d[$prob->ptag]['s'] = "nottried";
            }

            $json["scoreboard"][] = $d;
            
        }
        return json_encode($json);
    }
}
