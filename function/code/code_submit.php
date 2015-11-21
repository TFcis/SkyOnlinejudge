<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}


if(!userControl::checktoken('CODEPAD_EDIT') || !isset($_POST['code']) )
{
    throwjson('error','Access denied');
}
    
if( $_G['uid']==0 && $_E['Codepad']['allowguestsubmit'] == false )
    throwjson('error','Access denied');
    
$code = $_POST['code'];

if( empty($code) )
    throwjson('error','Empty!');
if( ($s=strlen($code)) > 15000 )
    throwjson('error','Too LONG! :'.$s);

$table = DB::tname('codepad');
$times = 10;

do{
    $times -- ;
    $hash = substr(md5(uniqid(uniqid(),true)),0,8);
    $uid = $_G['uid'];
    $res = SQL::query("INSERT INTO $table (`id`, `owner`, `hash`,`timestamp`,`content`) 
                                VALUES (NULL,?,?,NULL,?)",array($uid,$hash,$code));
}while( !$res && $times>0 );

if( $times <= 0)
    throwjson('error','FULL');
throwjson('SUCC',$hash);