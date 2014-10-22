<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}
$class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');

$id='error';
$data = false;

$tbstats = DB::tname('statsboard');
$unamet = DB::tname('account');
$uojt = DB::tname('userojlist');

$_E['template']['dbg'] = '';

//Check id
if( isset($_GET['id']) )
{
    $id = $_GET['id'];
    if(!preg_match('/^[0-9]+$/',$id))
    {
        $id = 'error';
    }
}

if( !(isset($_GET['id']) && ($setting = getCBdatabyid($id) )) )
{
    $_E['template']['alert'].="沒有這一個記分板";
    include('rank_list.php');
    exit('');
}
$_E['template']['title'] = $setting['name'];


#preprocess user data to $userid
#notice  $userid[$uid] $uid is INT

$userarray = expand_userlist($setting['userlist']);
$user = DB::getuserdata('account',$userarray,'`uid`,`nickname`');
$useracct = DB::getuserdata('userojlist',$userarray);
$userid = array();

//delete not availbe uid
$userarray = array();
foreach($user as $uid => $data)
{
    $userarray[ ]= (int)$uid;
}

foreach($userarray as $uid)
{
    if(isset( $useracct[(string)$uid] )){
        $userid[$uid]= ojid_reg($useracct[(string)$uid]['data']);
    }
    else{
        $userid[$uid]= ojid_reg('');
    }
    //add information before ojid_reg();
    $userid[$uid]['nickname'] = $user[$uid]['nickname'];
}

#preprocess prob data to probinfo
$prob = explode(',',$setting['problems']);
$probinfo = array();
$prelist = array();
#分類
foreach($prob as $pname)
{
    $probdata['name'] = $pname;
    $probdata['show'] = $pname;
    $probdata['oj']   = '';
    foreach($class as $cn => $c)
    {
        if( preg_match( $c->pattern, $pname ) )
        {
            $probdata['oj'] = $cn;
            $prelist[$cn][] = $pname;
            $_E['template']['dbg'].=$pname." match ".$cn."<br>";
            break;
        }
    }
    $probinfo[$pname] = $probdata;
}

//送入預處理
foreach($prelist as $name => $arr)
{
    if( method_exists($class[$name],'preprocess') )
    {
        $classid = array();
        foreach($userid as $u)
            if($u[$name])
                $classid[]=$u[$name]['acct'];
        $class[$name]->preprocess($classid ,$arr);
    }
    if( method_exists($class[$name],'showname') )
    {
        foreach($arr as $pn)
            $probinfo[$pn]['show'] = $class[$name]->showname($pn);
    }
}


$_E['template']['plist'] = $probinfo;
$_E['template']['user'] = $userid;
$_E['template']['owner'] = $setting['owner'];
$_E['template']['id'] = $setting['owner'];
foreach($userid as $uid => $u)
{
    foreach($probinfo as $p)
    {
        if($p['oj'] && $u[$p['oj']]['acct'])
            $re = $class[$p['oj']]->query($u[$p['oj']]['acct'],$p['name']);
        else
            $re='NO';
        switch($re)
        {
            case 0 : 
                $_E['template']['s'][$uid][$p['name']] = 'NO';
                break;
            case 9 :
                $_E['template']['s'][$uid][$p['name']] = 'AC';
                break;
        }
    }
}
Render::render('rank_statboard_cm','rank');