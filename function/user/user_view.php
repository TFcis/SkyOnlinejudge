<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}
// Check Who will viewed
$showid = $_G['uid'];
if( isset($_GET['id']) )
{
    $tid = $_GET['id'];
    if( is_numeric ($tid) ){
        $showid = $tid;
    }
    else{
         $_E['template']['alert'] = 'WTF!?';
    }
}

if($showid == 0)
{
    $_E['template']['alert'] = 'WTF!? NO Such One.';
    Render::render('nonedefined');
    exit('');
}
$_E['template']['showid'] = $showid;

if( isset($_GET['page']) )//subpage
{
    $require = $_GET['page'];
    switch($require)
    {
        case 'modify':
            if($showid == $_G['uid'])
            {
                userControl::registertoken('EDIT',3600);
                page_ojacct($showid);
                Render::renderSingleTemplate('user_data_modify_acct','user');
            }
            else
                Render::renderSingleTemplate('nonedefined');
            break;
        default:
            Render::renderSingleTemplate('nonedefined');
    }
    exit('');
}
else //main page
{
    $opt = array();
    if( !($opt = prepareUserView($showid)) )
    {
        $_E['template']['alert'] = 'WTF!? SQL ERROR.';
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
