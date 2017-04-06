<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class class_RandomSet extends ContestManger
{
    const VERSION = '0.1-alpha';
    const NAME = 'RandomSet';
    const DESCRIPTION = 'IOI Style with RandomSet Contest';
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
        return ['view_scoreboard_random_ioi','contest'];
    }
    
    public function resolver_template():array
    {
        return ['bangkok_resolver_ioi','contest'];
    }
    /**
     * 避免相鄰 team id 同題目
     */
    private function fetch_user_problems_json(\SKYOJ\Contest $constst,int $uid):array
    {
        try{
            $json = $constst->get_user_info($uid)->note;
            $json = json_decode($json);
            return $json??[];
        }catch(\SKYOJ\CommonObjectError $e){
            if( $e->getCode() !== \SKYOJ\SKY_ERROR::NO_SUCH_DATA )
                throw $e;
            return [];
        }
    }

    private function set_user_problems_json(\SKYOJ\Contest $constst,int $uid,string $json):void
    {
        //Contest not support set value for use, we sould do that
        $table = \DB::tname("contest_user");
        $cont_id = $constst->cont_id();
        if( \DB::queryEx("UPDATE `{$table}` SET `note`=? WHERE `cont_id`=? AND `uid`=?",$json,$cont_id,$uid)===false )
        {
            throw new \SKYOJ\CommonObjectError("Update user data error!",\SKYOJ\SKYOJ_ERROR::UNKNOWN_ERROR);
        }
    }

    //return pids
    private function TFCIS_EXAM_STYLE(\SKYOJ\Contest $constst,int $uid):array
    {
        $problems = $this->fetch_user_problems_json($constst,$uid);
        if( !empty($problems) )
            return $problems;

        $allprob = $constst->get_all_problems_info();
        $pset = [];
        foreach( $allprob as $row )
        {
            $pset[$row->ptag] [] = $row->pid;
        }

        $uprev = $this->fetch_user_problems_json($constst,$uid-1);
        $unext = $this->fetch_user_problems_json($constst,$uid+1);
        $upids = [];
        foreach( $pset as $ptag => $pids )
        {
            $size = count($pids);
            if( $size == 1 )
            {
                $upids []= (int)$pids[0];
            }
            elseif( $size == 2 )
            {
                $hash = $uid+strtotime($constst->timestamp);
                $upids []= (int)$pids[ $hash&1 ];
            }
            else
            {
                do{
                    $select = $pids[random_int(0,$size-1)];
                }while( in_array($select,$uprev) || in_array($select,$unext) );
                $upids []= (int)$select;
            }
        }

        $this->set_user_problems_json($constst,$uid,json_encode($upids));
        return $upids;
    }

    public function get_user_problems_info(\SKYOJ\Contest $constst,int $uid)
    {
        $rowdata = $constst->get_all_problems_info();
        $probs = $this->TFCIS_EXAM_STYLE($constst,$uid);
        $real = [];
        $set = [];
        foreach($probs as $pid)
            $set[$pid]=true;

        foreach( $rowdata as $row )
            if( isset($set[$row->pid]) )
                $real[] = $row;
        return $real;
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

        $pset = [];
        $userallowprobs = [];
        $pid2ptag = [];
        foreach($pids as $row)
        {
            $pset[$row->ptag] = true;
            $pid2ptag[$row->pid]=$row->ptag;
        }

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

            $userallowprobs[$uid] = [];
            $pids = json_decode($user->note)??[];
            foreach($pids as $pid)
                $userallowprobs[$uid][$pid] = true;

            $scoreboard[$uid]=[];
            foreach($pset as $ptag => $pids)
            {
                $scoreboard[$uid][$ptag]=new \SKYOJ\ScoreBlock();
                $scoreboard[$uid][$ptag]->try_times = 0;
                $scoreboard[$uid][$ptag]->is_ac     = 0;
                $scoreboard[$uid][$ptag]->ac_time   = 0;
                $scoreboard[$uid][$ptag]->firstblood= 0;
                $scoreboard[$uid][$ptag]->score     = 0;
                if( !$probleminfo_build )
                {
                    $probleminfo[$ptag] = new \SKYOJ\ProblemBlock();
                    $probleminfo[$ptag]->pid = 0;
                    $probleminfo[$ptag]->ptag = $ptag;
                    $probleminfo[$ptag]->try_times = 0;
                    $probleminfo[$ptag]->ac_times  = 0;
                }
            }
            $probleminfo_build = true;
        }

        $acset = [];
        foreach( $all as $row )
        {
            $uid=$row['uid'];
            $pid=$row['pid'];
            $ptag = $pid2ptag[$row['pid']];
            $verdict=$row['result'];
            $time=strtotime($row['timestamp'])-strtotime($contest->starttime);
            if( !isset($userallowprobs[$uid][$pid]) )continue;unset($pid);
            if( $scoreboard[$uid][$ptag]->is_ac != 0 )continue;
            if( $userallowprobs[$uid])

            $scoreboard[$uid][$ptag]->try_times++;
            $probleminfo[$ptag]->try_times++;
            if( $row['score'] > $scoreboard[$uid][$ptag]->score )
            {
                $delta = $row['score'] - $scoreboard[$uid][$ptag]->score; 
                $scoreboard[$uid][$ptag]->score = $row['score'];
                $userinfo[$uid]->score += $delta;
            }
            if( $verdict == \SKYOJ\RESULTCODE::AC )
            {
                $scoreboard[$uid][$ptag]->is_ac = 1;
                $scoreboard[$uid][$ptag]->ac_time = (int)floor($time/60); 
                if( !isset($acset[$ptag]) )
                {
                    $acset[$ptag] = 1;
                    $scoreboard[$uid][$ptag]->firstblood = 1;
                }
                $userinfo[$uid]->total_submit+=$scoreboard[$uid][$ptag]->try_times;
                $userinfo[$uid]->ac_time+=(int)floor(($time + ($scoreboard[$uid][$ptag]->try_times-1)*$contest->penalty)/60);
                $userinfo[$uid]->ac++;
                $probleminfo[$ptag]->ac_times++;
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
            $d['points'] = (int)$user->score;

            $nickname=\SKYOJ\nickname($user->uid);
            $d['name'] = $nickname[$user->uid];
            $d['group'] = '';

            foreach($scordboard_data['probleminfo'] as $prob)
            {
                $sb=$scordboard_data['scoreboard'][$user->uid][$prob->ptag];
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
