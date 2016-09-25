<?php namespace SKYOJ\Challenge;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function listHandle()
{
    global $SkyOJ,$_E;
    $page = $SkyOJ->UriParam(2)??'1';
    $uid = \SKYOJ\safe_get('uid')??null;
    $pid = \SKYOJ\safe_get('pid')??null;
    $result = \SKYOJ\safe_get('result')??null;

    if( !preg_match('/^[1-9][0-9]*$/',$page) )
        $page = '1';
    $addtion = [];
    if( isset($uid)&&preg_match('/^[1-9]\d*$|^0$/',$uid) )$addtion[] = ['uid',(int)$uid];
    if( isset($pid)&&preg_match('/^[1-9]\d*$|^0$/',$pid) )$addtion[] = ['pid',(int)$pid];
    if( isset($result)&&preg_match('/^[1-9]\d*$|^0$/',$result) )$addtion[] = ['result',(int)$result];

    if( !empty($addtion) ){
        $t = '';
        $query = '?';
        foreach($addtion as $row)
        {
            $t.= "`{$row[0]}`={$row[1]} AND ";
            $query.="{$row[0]}={$row[1]}&";
        }
        $t.='1';
        $pl = new PageList('challenge',$t);
    }else{
        $pl = new PageList('challenge');
    }

    $data = $pl->GetPageDataByPage($page,'cid','*','DESC');
    $pids = [];
    foreach($data as $row)
        $pids[] = $row['pid'];

    //LOG::msg(Level::Debug, '', $data);
    $_E['template']['challenge_list_pagelist'] = $pl;
    $_E['template']['challenge_list_now'] = $page;
    $_E['template']['challenge_info'] = $data ? $data : [];
    $_E['template']['challenge_query'] = $query??'';
    $_E['template']['challenge_prob'] = \userControl::getuserdata('problem',$pids,['owner','content_access'],'pid');

    \Render::render('challenge_list', 'challenge');
}
