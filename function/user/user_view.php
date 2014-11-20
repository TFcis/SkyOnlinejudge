<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
// Check Who will viewed
if( isset($_GET['id']) )
{
    $tid = safe_get('id');
    if( is_numeric ($tid) ){
        $showid = $tid;
    }
    else{
         $_E['template']['alert'] = 'WTF!?';
         Render::render('nonedefined');
         exit('');
    }
}
else
{
    $showid = $_G['uid'];
}
$tmp = DB::getuserdata('account',$showid,'uid');
if( $tmp===false || !isset($tmp[$showid] ))
{
    $_E['template']['alert'] = 'QQ NO Such One.';
    Render::render('nonedefined');
    exit('');
}
$_E['template']['showid'] = $showid;

if( isset($_GET['page']) )//subpage
{
    $require = safe_get('page');
    switch($require)
    {
        case 'modify':
            if( userControl::getpermission($showid) )
            {
                userControl::registertoken('EDIT',3600);
                page_ojacct($showid);
                Render::renderSingleTemplate('user_data_modify_acct','user');
                exit(0);
            }
            break;
        case 'setting':
            if( userControl::getpermission($showid) )
            {
                userControl::registertoken('EDIT',3600);
                page_ojacct($showid);
                Render::renderSingleTemplate('user_data_modify','user');
                exit(0);
            }
            break;
    }
    Render::renderSingleTemplate('nonedefined');
    exit(0);
}
else //main page
{
    $opt = array();
    if( !($opt = prepareUserView($showid)) )
    {
        $_E['template']['alert'] = 'WTF!? 查無此人.';
        Render::render('nonedefined');
        exit('');
    }
    else
    {
        $_E['template']['avaterurl'] = "http://www.gravatar.com/avatar/$showid?d=identicon&s=400";
        $_E['template']['nickname'] =  htmlspecialchars($opt['nickname']);
        $_E['template']['quote'] =  htmlspecialchars("Sylveon (Japanese: ニンフィア Nymphia) is a Fairy-type Pokémon.It evolves from Eevee when leveled up knowing a Fairy-type move and having at least two Affection hearts in Pokémon-Amie. It is one of Eevee's final forms, the others being Vaporeon, Jolteon, Flareon, Espeon, Umbreon, Leafeon, and Glaceon.");
        Render::render('user_view','user');
    }
}
