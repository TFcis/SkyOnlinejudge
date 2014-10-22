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
        if( intval($user) == $user)
        {
            $users[]=intval($user);
        }
        if( preg_match('/(\d+)-(\d+)/',$user,$match) )
        {
            $a = intval($match[1]);
            $b = intval($match[2]);
            if($a && $b)
            {
                if($a > $b)
                    list($a,$b) = array($b,$a);
                for(;$a<=$b;$a++)
                {
                    $users[]=$a;
                }
            }
        }
    }
    sort($users);
    return array_unique($users);
}