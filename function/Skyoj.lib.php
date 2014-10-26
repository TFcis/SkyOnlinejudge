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

function expand_userlist($string)
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

function expand_promlemlist($problems)
{
    $substr = array();
    $stack = 0;
    $pos = 0;
    $problems = str_replace('*','',$problems);
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
                if( $sb = expand_promlemlist($substr[$subnum++]) )
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
            $problist=array_merge($problist,$res);
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