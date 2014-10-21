<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

function page_range($all,$row,$now,$div)
{
    $all = intval($all);
    $row = intval($row);
    $now = intval($now);
    $div = intval($div);
    
    $maxR = 1+intval( ($all-1)/$row );
    
    if($now<1)$now=1;
    if($now>$maxR)$now=$maxR;
    
    $L = max(1,$now-$div);
    $d = $div - ($now-$L);
    $R = min($maxR,$now+$d+$div);
    return array($L,$now,$R);
}

function getCBdatabyid($id)
{
    $tbstats = DB::tname('statsboard');
    if(!preg_match('/^[0-9]+$/',$id))
    {
        return false;
    }
    if($res = DB::query("SELECT * FROM `$tbstats` WHERE `id` = $id"))
        return DB::fetch($res);
    return false;
}