<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

$class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');

$_E['template']['rank_site'] = array();
$_E['template']['dbg'] = '';

foreach($class as $site => $c)
{
    $_E['template']['rank_site'][$site]['name']   = $c->name;
    $_E['template']['rank_site'][$site]['author'] = $c->copyright;
    $_E['template']['rank_site'][$site]['desc']   = $c->description;
    $_E['template']['rank_site'][$site]['version']= $c->version;
    $_E['template']['rank_site'][$site]['format'] = htmlspecialchars($c->pattern);
}
//test
$userid   = array(3,7,18,17,46,10,26,30);
$problist = "toj64,toj100,toj101,toj102,toj103,zosj01,toj159,toj160,toj161,toj162,toj163,toj164,toj165,toj166";
//select
$prob = explode(',',$problist);
$probinfo = array();
$prelist = array();

foreach($prob as $pname)
{
    $probdata['name'] = $pname;
    $probdata['oj']   = '';
    foreach($class as $cn => $c)
    {
        if( preg_match( $c->pattern, $pname ) )
        {
            $probdata['oj'] = $cn;
            $prelist[$cn][] = $pname;
            $_E['template']['dbg'].=$pname." match ".$cn."<br>";
            if( method_exists($c,'showname') )
            {
                $pname = $c->showname($pname);
            }
            break;
        }
    }
    $probinfo[] = $probdata;
}

//preprocess
foreach($prelist as $name => $arr)
{
    if( method_exists($class[$name],'preprocess') )
    {
        $class[$name]->preprocess($userid ,$arr);
    }
}

$_E['template']['board'] = array();
$_E['template']['plist'] = $prob;
$_E['template']['id'] = $userid;

foreach($userid as $u)
{
    foreach($probinfo as $p)
    {
        if($p['oj'])
            $re = $class[$p['oj']]->query($u,$p['name']);
        else
            $re='NO';
        switch($re)
        {
            case 0 : 
                $_E['template']['s'][$u][$p['name']] = 'NO';
                break;
            case 9 :
                $_E['template']['s'][$u][$p['name']] = 'AC';
                break;
        }
    }
}

Render::render('rank_index','rank');