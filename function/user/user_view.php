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
        Render::errormessage('WTF!?');
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
    Render::errormessage('QQ NO Such One.');
    Render::render('nonedefined');
    exit('');
}
$_E['template']['showid'] = $showid;

if( isset($_GET['page']) )//subpage
{
    
    $require = safe_get('page');
    switch($require)
    {
        case 'setting':
            if( userControl::getpermission($showid) )
            {
                Render::renderSingleTemplate('user_setting','user');
                exit(0);
            }
        case 'account':
            if( userControl::getpermission($showid) )
            {
                userControl::registertoken('EDIT',3600);
                Render::renderSingleTemplate('user_data_modify_account','user');
                exit(0);
            }
            break;
        case 'ojacct':
            if( userControl::getpermission($showid) )
            {
                userControl::registertoken('EDIT',3600);
                page_ojacct($showid);
                Render::renderSingleTemplate('user_data_modify_ojacct','user');
                exit(0);
            }
            break;
        case 'myboard':
            if( userControl::getpermission($showid) )
            {
                #WAIT FOR PRESYSTEM
                $statsboard = DB::tname('statsboard');
                $res = DB::query("SELECT `id`,`name` FROM `$statsboard` WHERE `owner` = '$showid'");
                $rowdata = array();
                
                if( !$res  )
                {
                    $_E['template']['message'] = 'SQL Error...';
                    Render::renderSingleTemplate('common_message','common');
                    exit(0);
                }
                while( $data = DB::fetch($res) )
                    $rowdata[]=$data;
                $_E['template']['row'] = $rowdata;
                Render::renderSingleTemplate('user_data_modify_myboard','user');
                exit(0);
            }
            break;
    }
    Render::renderSingleTemplate('nonedefined');
    exit(0);
}
else //main page
{
    $userInfo = new UserInfo($showid);
    $opt = $userInfo->load_data('view');
    if( $opt !==false )
    {
        $_E['template'] = array_merge( $_E['template'] , $opt );
        #if use gravatar
        $_E['template']['avaterurl'] .= "s=400&";
        Render::render('user_view','user');
        exit(0);
    }
    else
    {
        Render::errormessage('WTF!? 查無此人.');
        Render::render('nonedefined');
        exit(0);
    }
}
exit(0);
