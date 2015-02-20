<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}


if(!userControl::checktoken('CODEPAD_EDIT') || !isset($_POST['code']) )
{
    var_dump($_COOKIE);
    throwjson('error','Access denied');
}
    
if( $_G['uid']==0 && $_E['Codepad']['allowguestsubmit'] == false )
    throwjson('error','Access denied');
    
$code = $_POST['code'];
$storgepath = $_E['ROOT'].'/data/codepad/';

if( empty($code) )
    throwjson('error','Empty!');
if( ($s=strlen($code)) > 15000 )
    throwjson('error','Too LONG! :'.$s);

do{
    $name = md5(uniqid(uniqid(),true)).".code";
}while( file_exists($storgepath.$name) );

$handle = fopen( $storgepath.$name , 'w+' );
if( !$handle )
    throwjson('error','SYS denied');
fwrite($handle,$code);
fclose($handle);
$table = DB::tname('codepad');
$times = 10;

do{
    $times -- ;
    $hash = substr(md5(uniqid(uniqid(),true)),0,8);
    $uid = $_G['uid'];
    $res = DB::query("INSERT INTO $table (`id`, `owner`, `hash`, `filename`, `timestamp`) 
                                VALUES (NULL,'$uid','$hash','$name',NULL)");
}while( !$res && $times>0 );

if( $times <= 0)
    throwjson('error','FULL');
throwjson('SUCC',$hash);