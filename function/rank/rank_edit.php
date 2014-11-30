<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

//Only By Post Login Token
if(!isset($_POST['mod']) || !$_G['uid'] || !userControl::checktoken('CBEDIT'))
    throwjson('error','Access denied');
$allowpage = array('cbedit','cbremove');

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
        $removecache = false;
        $argv=array('name','userlist','problems','announce');
        $cb = array();
        //Get CB Data
        if($id === '0'){
            $cb['owner']=$_G['uid'];
            $cb['state']='1';
            $cb['id']='NULL';
            $cb['userlist']='';
            $cb['problems']='';
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
        if( !extend_userlist($data['userlist']) ){
            throwjson('error','user list error');
        }
        if( !extend_promlemlist($data['problems']) ){
            throwjson('error','problem list error');
        }
        
        //Check if need rebuild
        if( $data['userlist'] !== $cb['userlist'] ||
            $data['problems'] !== $cb['problems']){
            $removecache = true;
        }
        
        if( !userControl::isAdmin($_G['uid']) )
        {
            $data['announce'] = strip_tags($data['announce']);
        }
        
        $cid = $cb['id'];
        $cowner = $cb['owner'];
        $cts = $cb['state'];
        
        foreach($argv as $ag)
            $data[$ag] = addslashes($data[$ag]);
        
        $cname =$data['name'];
        $cul =  $data['userlist'];
        $cps =  $data['problems'];
        $cannounce = $data['announce'];

        if($res= DB::query("INSERT INTO `$tb`
            (`id`, `name`, `owner`, `timestamp`, `userlist`, `problems`, `announce` ,`state`) VALUES
            ($cid,'$cname',$cowner,CURRENT_TIMESTAMP,'$cul','$cps','$cannounce',$cts)
            ON DUPLICATE KEY UPDATE
            `name` = '$cname',
            `userlist` = '$cul',
            `problems` = '$cps',
            `announce` = '$cannounce' "))
        {
            if( $cb['id'] == 'NULL' )
            {
                $cb['id'] = mysql_insert_id();
            }
            userControl::deletetoken('CBEDIT');
            if($removecache)
            {
                DB::deletecache("cache_board_".$cb['id']);
            }
            throwjson('SUCC',$cb['id']);
        }
        else
        {
            throwjson('error','sqlerror!');
        }
        break;
    case 'cbremove':
         throwjson('error','sql_cbremove');
        break;
    default:
        throwjson('error','modify');
        break;
}

throwjson('error','error');