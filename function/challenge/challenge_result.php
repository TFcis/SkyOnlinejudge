<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

if(isset($QUEST[1]))
{
    $rid=$QUEST[1];
}
else
{
    Render::render('nonedefined');
    exit('');
}

$table=DB::tname('challenge');
$pdo=DB::prepare("SELECT * FROM `$table` WHERE `id` = ?");
if(DB::execute($pdo,array($rid)))
{
    $data=$pdo->fetchAll();
}

if(isset($data))
{
    $_E['template']['challenge_result_info'] = $data?$data:array();
}
LOG::msg(Level::Debug,"",$data);
Render::render("challenge_result",'challenge');
?>