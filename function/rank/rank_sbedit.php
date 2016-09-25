<?php namespace SKYOJ\Rank;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function sbeditHandle()
{
    global $SkyOJ,$_E,$_G;

    $sb_id = $SkyOJ->UriParam(2)??null;

    $default = [];
    $default['name'] = '';
    $default['owner'] = $_G['uid'];
    $default['userlist'] = '';
    $default['problems'] = '';
    $default['announce'] = '';
    $default['state'] = '1';
    $default['sb_id'] = '0';

    try{
        if( !\userControl::isAdmin($_G['uid']) )
            throw new \Exception('Access denied');
        //TODO: Check $sb_id format
        if( !isset($sb_id)||$sb_id==0 )
        {
            $_E['template']['title'] = 'NEW!';
            $_E['template']['form'] = $default;
        }
        else
        {
            if (!($setting = getCBdatabyid($sb_id)))
                throw new \Exception('沒有這一個記分板');
            if (!userControl::getpermission($setting['owner']))
                throw new \Exception('Access denied');
            $_E['template']['title'] = $setting['name'];
            $_E['template']['form'] = $setting;
        }
        \Render::render('rank_sbedit', 'rank');
    }catch(\Exception $e){
        \Render::errormessage($e->getMessage());
        \Render::render('nonedefined');
    }
}

//class
/*$class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');

$_E['template']['rank_site'] = [];
foreach ($class as $site => $c) {
    $_E['template']['rank_site'][$site]['name'] = $c->name;
    $_E['template']['rank_site'][$site]['author'] = $c->copyright;
    $_E['template']['rank_site'][$site]['desc'] = $c->description;
    $_E['template']['rank_site'][$site]['version'] = $c->version;
    $_E['template']['rank_site'][$site]['format'] = htmlspecialchars($c->pattern);
}*/
