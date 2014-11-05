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

function checkpostdata($array)
{
    $res = array();
    foreach($array as $p)
    {
        if(!isset($_POST[$p]))
            return false;
        $res[$p] = $_POST[$p];
    }
    return $res;
}

function verdictIDtoword($vid)
{
    $res = 'NO';
    switch($vid)
    {
        case 10 : $res='SE';break;#Submission error
        case 15 : $res='NJ';break;#Can't be judged
        case 20 : $res='IQ';break;#In queue
        case 30 : $res='CE';break;#Compile error
        case 35 : $res='RF';break;#Restricted function
        case 40 : $res='RE';break;#Runtime error
        case 45 : $res='OE';break;#Output limit
        case 50 : $res='TLE';break;#Time limit
        case 60 : $res='MLE';break;#Memory limit
        case 70 : $res='WA';break;#Wrong answer
        case 80 : $res='PE';break;#PresentationE
        case 90 : $res='AC';break;#Accepted
    }
    return $res;
}

function buildcbboard($bid)
{
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
    $res =array();
    #check bid
    if( !is_numeric($bid) )
        return false;
    $bid = intval($bid);
    if($bid <= 0)
        return false;
        
    $boarddata = getCBdatabyid($bid);
    if(!$boarddata)
        return false;
    
    $res['name']  = $boarddata['name'];
    $res['owner'] = $boarddata['owner'];
    $res['createtime'] = $boarddata['timestamp'];
    $res['userlist'] = extend_userlist($boarddata['userlist']);
    $res['problems'] = extend_promlemlist($boarddata['problems']);
    
    if( $res['userlist']===false )
        $res['userlist'] = array();
    if( $res['problems']===false )
        $res['problems'] = array();
    
    #get oj account
    $userojacct_sql = DB::getuserdata('userojlist', $res['userlist'] );
    $userojacct = array();
    $emptyacct = ojid_reg('');
    foreach( $res['userlist'] as &$uid )
    {
        $uid = (string)$uid;
        if( isset($userojacct_sql[$uid]) )
            $userojacct[$uid] = ojid_reg( $userojacct_sql[$uid]['data'] ); 
        else
            $userojacct[$uid] = $emptyacct;
    }
    
    #setproblem
    $problist_sorted = array();
    $probleminfo = array();
    #分類
    foreach($res['problems'] as $pname)
    {
        $probdata['name'] = $pname;
        $probdata['show'] = $pname;
        $probdata['oj']   = '';
        foreach($class as $classname => $c)
        {
            if( preg_match( $c->pattern, $pname ) )
            {
                $probdata['oj'] = $classname;
                $problist_sorted[$classname][] = $pname;
                break;
            }
        }
        $probleminfo[$pname] = $probdata;
    }
    
    #預處理
    foreach($problist_sorted as $classname => $arr)
    {
        if( method_exists( $class[$classname],'preprocess') )
        {
            $class_acct = array();
            foreach($userojacct as $acct)
                if( $acct[$classname] )
                    $class_acct[]=$acct[$classname]['acct'];
            $class[$classname]->preprocess($class_acct ,$arr);
        }
        if( method_exists($class[$classname],'showname') )
        {
            foreach($arr as $pn)
            {
                $probleminfo[$pn]['show'] = $class[$classname]->showname($pn);
            }
        }
    }
    
    #set problem data and user acct to res
    $res['userojacct'] = $userojacct;
    $res['probinfo']   = $probleminfo;
    $res['ratemap'] = array();
    
    if( !( $accache = DB::loadcache("rate_ac_cb_$bid") ) )
    {
        $accache = array();
    }
    #ratemap
    foreach($userojacct as $uid => $u)
    {
        foreach($probleminfo as $pname => $p)
        {
            if( array_key_exists( $uid,$accache) && isset($accache[$uid][$p['name']]) )
                $re = 90;
            elseif( $p['oj'] && $u[ $p['oj'] ]['acct'] )
                $re = $class[$p['oj']]->query($u[$p['oj']]['acct'],$p['name']);
            else
                $re=0;
            $res['ratemap'][$uid][$p['name']] = $re;
            
            //AC
            if($re == 90)
                $accache[$uid][$p['name']] = 1;
        }
    }
    DB::putcache("rate_ac_cb_$bid",$accache,86400);
    return $res;
}