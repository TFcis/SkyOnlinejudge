<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

$class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
$sitelist = $class[0];
$class = $class[1];
$_E['template']['rank_site'] = array();
$_E['template']['dbg'] = '';

foreach($sitelist as $site)
{
    $_E['template']['rank_site'][$site]['name']   = $class[$site]->name;
    $_E['template']['rank_site'][$site]['author'] = $class[$site]->copyright;
    $_E['template']['rank_site'][$site]['desc']   = $class[$site]->description;
    $_E['template']['rank_site'][$site]['version']= $class[$site]->version;
    $_E['template']['rank_site'][$site]['format'] = htmlspecialchars($class[$site]->pattern);
}
//test
$userid   = array(3,7,18,17,46,18,10,26,30);
$problist = "toj64,toj100,toj101,toj102,toj103,zosj01,toj159,toj160,toj161,toj162,toj163,toj164,toj165,toj166";
//select
$prob = explode(',',$problist);
$probinfo = array();
$prelist = array();
$i = 0;
//var_dump($class);
foreach($prob as $pname)
{
    $probinfo[$i]['name'] = $pname;
    $probinfo[$i]['oj']   = '';
    
    foreach($class as $cn => $c)
    {
        if( preg_match( $c->pattern, $pname ) )
        {
            $probinfo[$i]['oj'] = $cn;
            $prelist[$cn][] = $pname;
            $_E['template']['dbg'].=$pname." match ".$cn."<br>";
            break;
        }
    }
    $i++;
}
foreach($prelist as $name => $arr)
{
    $_E['template']['dbg'].=$name."<br>";
    if( method_exists($class[$name],'preprocess') )
    {
        $class[$name]->preprocess($userid ,$arr);
        $_E['template']['dbg'].="pre $name<br>";
    }
    else
    {
        $_E['template']['dbg'].="none<br>";
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