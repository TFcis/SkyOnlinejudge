<?php namespace SKYOJ\User;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

//          0   1    2     3
// FORMAT /VIEW/id
//                /page
//                       /subpage
// Check Who will viewed
function ViewHandle()
{
    global $SkyOJ,$_E,$_G;

    $templateAsk = $subpage = \SKYOJ\safe_get('subpage')!==null;
    $page = $SkyOJ->UriParam(3)??'setting';
    $page4= $SkyOJ->UriParam(4);
    //Deal with showid and user info
    try{
        $showid = (int)( $SkyOJ->UriParam(2) ?? $_G['uid'] );
        $userInfo = new UserInfo($showid);
        if( !$userInfo->is_registed() ){
            throw new \Exception('QQ NO Such One.');
        }
        $_E['template']['showid'] = $showid;
    }catch (\Throwable $e){
        \Render::errormessage('ERROR : '.$e->getMessage(),'USER');
        \Render::render('nonedefined');
        exit();
    }
    
    if( !$subpage )
    {
        $opt = $userInfo->load_data('view');
        

        if ($opt !== false) {
            $_E['template'] = array_merge($_E['template'], $opt);

            //if use gravatar
            if (empty($opt['avaterurl'])) {
                $_E['template']['avaterurl'] = getgravatarlink($userInfo->account('email')).'s=400&';
            }
            
            if( !preg_match('/^[a-z]+$/',$page) )
                $page = '';

            $_E['template']['view']['defaultpage'] = $page;

            if ( !empty($page4)) {
                $_SESSION['QUEST4'] = $page4;
            }
            \Render::render('user_view', 'user');
            exit(0);
        } else {
            \Render::errormessage('Load Data Error!');
            \Render::render('nonedefined');
            exit(0);
        }
        \SKYOJ\NeverReach();
    }

    switch ($page) {
        case 'setting':
            if (!\userControl::getpermission($showid)) {
                \Render::renderSingleTemplate('nonedefined');
                exit(0);
            }
            break;
        default:
            \Render::renderSingleTemplate('nonedefined');
            exit(0);
    }

    $funcpath = $_E['ROOT']."/function/user/user_$page.php";
    $func     = __NAMESPACE__ ."\\{$page}Handle";

    require_once($funcpath);
    $func($userInfo);
}
