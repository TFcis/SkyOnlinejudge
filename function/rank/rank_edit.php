<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

//Only By Post Login Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('CBEDIT'))
    throwjson('error','Access denied');
$allowpage = array('cbedit');

$editpage = isset($_POST['page'])?$_POST['page']:'';
if(!in_array($editpage ,$allowpage))
    throwjson('error','No such page');

$id = isset($_POST['id'])?$_POST['id']:'';
if(!preg_match('/^[0-9]+$/',$id))
    throwjson('error','ID error');
$id = (string)((int)$id);

switch($editpage)
{
    case 'cbedit':
        $tb = DB::tname('statsboard');
        $argv=array('name','userlist','problems');
        $cb = array();
        //Get CB Data
        if($id === '0'){
            $cb['owner']=$_G['uid'];
            $cb['state']='1';
            $cb['id']='NULL';
        }
        elseif( !( $cb = getCBdatabyid($id)) ){
            throwjson('error','sqlerror');
        }
        //Check owner
        if( !userControl::getpermission($cb['owner']) ){
            throwjson('error','Not owner');
        }
        //Check data
        if( !( $data = checkpostdata($argv)) ){
            throwjson('error','cbedit');
        }
        $cid = $cb['id'];
        $cname = $data['name'];
        $cowner = $cb['owner'];
        $cul =$data['userlist'];
        $cps = $data['problems'];
        $cts = $cb['state'];
        foreach($argv as $ag)
            $data[$ag] = addslashes($data[$ag]);
        if($res= DB::query("INSERT INTO `$tb`
            (`id`, `name`, `owner`, `timestamp`, `userlist`, `problems`, `state`) VALUES
            ($cid,'$cname',$cowner,CURRENT_TIMESTAMP,'$cul','$cps',$cts)
            ON DUPLICATE KEY UPDATE
            `name` = '$cname',
            `userlist` = '$cul',
            `problems` = '$cps'"))
        {
        //var_dump($cb);
            if( $cb['id'] == 'NULL' )
            {
                $cb['id'] = mysql_insert_id();
            }
            throwjson('SUCC',$cb['id']);
        }
        else
        {
            throwjson('error','sql_cbedit');
        }
        break;
    default:
        throwjson('error','modify');
        break;
}

throwjson('error','error');