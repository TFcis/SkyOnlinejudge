<?php namespace SKYOJ\Contest;
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

function resolverHandle()
{
    global $SkyOJ,$_E,$_G;
    
    try{
        $cont_id = $SkyOJ->UriParam(2);
        $contest = GetContestByID($cont_id);

        if( !\userControl::isAdmin($_G['uid']))
            throw new \Exception('Admin Only!');
        if( $contest->ispreparing() )
            throw new \Exception('Contest is preparing!');

        $_E['template']['contest'] = $contest;
        //bangkok_resolver will destory page design
        \Render::renderSingleTemplate('common_header');
        $tmpl = $contest->resolver_template();
        \Render::renderSingleTemplate($tmpl[0],$tmpl[1]);
    }catch(\Exception $e){
        \Render::errormessage('Oops! '.$e->getMessage(),'Contest');
        \Render::render('nonedefine');
    }
}