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