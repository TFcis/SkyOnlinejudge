<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

//BASIC
function throwjson($status,$data)
{
    exit(json_encode(array('status'=>$status,'data'=>$data)));
}

function save_get($key)
{
    if(isset($_GET[$key]))
        return $_GET[$key];
    return false;
}

function save_post($key)
{
    if(isset($_POST[$key]))
        return $_POST[$key];
    return false;
}

function extend_userlist($string)
{
    $tmp = explode(',',$string);
    $users = array();
    foreach($tmp as $user)
    {
        $res = array();
        $user = trim($user);
        $flag = 'add';
        if( $user === '' ){
            continue;
        }
        if( $user[0] === '^'){
            $flag = 'remove';
            $user = preg_replace('/^\^/','',$user);
        }
        
        if( is_numeric($user) )
        {
            $res[]=intval($user);
        }
        else if( preg_match('/^(\d+)-(\d+)$/',$user,$match) )
        {
            $a = intval($match[1]);
            $b = intval($match[2]);
            if($a && $b){
                if($a > $b)
                    list($a,$b) = array($b,$a);
                for(;$a<=$b;$a++)
                {
                    $res[]=$a;
                }
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
        if($flag == 'add'){
            $users =array_merge($res,$users);
            $users =array_unique($users);
        }
        else //remove
        {
            foreach($res as $v)
            {
                $key = array_search($v,$users);
                if($key !== false)
                    unset($users[$key]);
            }
        }
    }
    sort($users);
    return array_unique($users);
}

function extend_promlemlist($problems)
{
    $substr = array();
    $stack = 0;
    $pos = 0;
    
    $problems = str_replace('*','',$problems);
    $problems = trim($problems);
    $len = strlen($problems);
    for( $i=0 ; $i<$len ;++$i )
    {
        if( $problems[$i] === '(' )
        {
            if($stack === 0)
                $pos = $i;
            $stack++;
        }
        elseif( $problems[$i] === ')' )
        {
            $stack--;
            if($stack === 0)
            {
                if($i+1 < $len && $problems[$i+1]!==','){
                    return false;
                }
                $sub = substr($problems,$pos+1,$i-$pos-1);
                $substr[]=$sub;
                for( ; $pos<=$i ;$pos++){
                    $problems[$pos]='*';
                }
            }
        }
    }
    
    $problems = preg_replace('/\*+/','*',$problems);
    $tmp = explode(',',$problems);
    $subnum = 0;
    $problist = array();
    
    foreach($tmp as $word)
    {
        $res = array();
        $flag = 'add';
        $word=trim($word);
        if(!$word)continue;
        if( $word[0] === '^'){
            $flag = 'remove';
            $word = preg_replace('/^\^/','',$word);
        }
        if( is_numeric($word[0]) )
        {
            if(is_numeric($word)){
                $res[]=$word;
            }
            elseif( preg_match('/^(\d+)-(\d+)$/',$word,$match) )
            {
                $a = intval($match[1]);
                $b = intval($match[2]);
                if($a && $b){
                    if($a > $b)
                        list($a,$b) = array($b,$a);
                    for(;$a<=$b;$a++)
                    {
                        $res[]=(string)$a;
                    }
                }
                else{
                    return false;
                }
            }
        }
        else
        {
            if( strpos($word,'*') === false )
            {
                $res[]=$word;
            }
            else
            {
                $word= str_replace('*','',$word);
                if( $sb = extend_promlemlist(trim($substr[$subnum++])) )
                {
                    foreach($sb as $w){
                        $res[]=$word.$w;
                    }
                }
                else
                {
                    return false;
                }
            }
        }
        if($flag == 'add'){
            $problist = array_merge($problist,$res);
            $problist = array_unique($problist);
        }else{
            foreach($res as $v)
            {
                $key = array_search($v,$problist);
                if($key !== false)
                    unset($problist[$key]);
            }
        }
    }
    return array_unique($problist);
}

function envadd($table)
{
    global $_E;
    $_E[$table] = array();
    $tb = DB::tname($table);
    if( $res = DB::query("SELECT * FROM `$tb`") )
    {
        while( $dat = DB::fetch($res) )
        {
            $_E[$table][]=$dat;
        }
        return true;
    }
    else
    {
        return false;
    }
}

function ojid_reg($json)
{
    global $_E;
    if( !isset($_E['ojlist']) )
        if( !envadd('ojlist') )
            return false;
            
    $ojname = array();
    foreach($_E['ojlist'] as $oj)
        $ojname[]=$oj['class'];
    
    if(! ($acct = json_decode($json,true)) )
        $acct = array();
        
    $oldacct = $acct;
    foreach($oldacct as $oj => $stats)
    {
        if(!in_array($oj,$ojname))
            unset($acct[$oj]);
    }
    
    foreach($ojname as $oj)
    {
        if(!isset($acct[$oj]))
        {
            $acct[$oj] = array(
                'acct' => '',
                'approve' => 0);
        }
    }
    return $acct;
}

function nickname( $uid )
{
    global $_E;
    if(!is_array($uid))
        $uid = array($uid);

    $res =  DB::getuserdata('account',$uid,'uid,nickname');
    foreach( $uid as $u )
    {
        $u=(string)$u;
        if(isset($res[$u]))
            $_E['nickname'][$u] = $res[$u]['nickname'];
    }
    return $res;
}