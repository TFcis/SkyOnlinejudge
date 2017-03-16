<?php namespace SKYOJ\Contest;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function CreateNewContestID(int $owner,string $title):int
{
    $tcontest = \DB::tname('contest');
    $res = \DB::queryEx("INSERT INTO `{$tcontest}` (`cont_id`, `owner`, `title`) 
                         VALUES (NULL,?,?)",$owner,$title);
    return $res?\DB::lastInsertId('cont_id'):null;
}

function GetContestByID($cont_id)
{
    if( !\SKYOJ\check_tocint($cont_id) )
        throw new \Exception('CONT_ID Error');
    $contest = new \SKYOJ\Contest($cont_id);
    if( $contest->isIdfail() )
        throw new \Exception('CONT_ID Error');
    return $contest;
}

function to_resolver_json($scordboard_data,\SKYOJ\Contest $contest)
{
    //solved attempted
    $json = [];
    $json["solved"] = [];
    $json["attempted"] = [];
    foreach($scordboard_data['probleminfo'] as $prob)
    {
        $json["solved"][$prob->ptag] = $prob->ac_times;
        $json["attempted"][$prob->ptag] = $prob->try_times;
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
            if( $sb->is_ac )
            {
                $d[$prob->ptag]['t'] = $sb->ac_time;
            }
            if( $sb->firstblood )$d[$prob->ptag]['s'] = "first";
            else if( $sb->is_ac )$d[$prob->ptag]['s'] = "solved";
            else if( $sb->try_times ) $d[$prob->ptag]['s'] = "tried";
            else $d[$prob->ptag]['s'] = "nottried";
        }

        $json["scoreboard"][] = $d;
        
    }
    return json_encode($json);
}