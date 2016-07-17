<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once $_E['ROOT'].'/function/common/forminfo.php';
//          0   1    2     3
// FORMAT /VIEW/id
//                /page
//                       /subpage
// Check Who will viewed
if (isset($_GET['id'])) {
    Render::errormessage('OLD MOD!?');
    Render::render('nonedefined');
    exit(0);
}

if (isset($QUEST[1])) {
    $tid = $QUEST[1];
    if (is_numeric($tid)) {
        $showid = $tid;
    } else {
        Render::errormessage('WTF!?');
        Render::render('nonedefined');
        exit('');
    }
} else {
    $showid = $_G['uid'];
}

//page
$templateAsk = false;
if (!isset($QUEST[2]) || empty($QUEST[2])) {
    $QUEST[2] = 'setting';
}

$token = safe_get('token');

//TEST
if ($token == 'tmpl') {
    $templateAsk = true;
}

$userInfo = new UserInfo($showid);
if (!$userInfo->is_registed()) {
    Render::errormessage('QQ NO Such One.');
    Render::render('nonedefined');
    exit('');
}
$_E['template']['showid'] = $showid;

if ($templateAsk === false) {
    // Print ALL PAGE

    //May be call subpage...
    $opt = $userInfo->load_data('view');
    if ($opt !== false) {
        $_E['template'] = array_merge($_E['template'], $opt);
        //if use gravatar
        if (empty($opt['avaterurl'])) {
            $_E['template']['avaterurl'] = getgravatarlink($userInfo->account('email')).'s=400&';
        }
        //$_E['template']['avaterurl'] .= "s=400&";
        $_E['template']['view']['defaultpage'] = 'setting';
        $view_allowpage = ['setting', 'summary', 'solve'];
        if (in_array($QUEST[2], $view_allowpage)) {
            $_E['template']['view']['defaultpage'] = $QUEST[2];
        } else {
            Render::errormessage('No Such Page');
            Render::render('nonedefined');
        }
        if (isset($QUEST[3]) && !empty($QUEST[3])) {
            $_SESSION['QUEST3'] = $QUEST[3];
        }
        Render::render('user_view', 'user');
        exit(0);
    } else {
        Render::errormessage('Load Data Error!');
        Render::render('nonedefined');
        exit(0);
    }
    //protect
    exit(0);
}
//subpage
switch ($QUEST[2]) {
    case 'setting':
        if (!userControl::getpermission($showid)) {
            Render::renderSingleTemplate('nonedefined');
            exit(0);
        }

        if (!isset($QUEST[3]) || empty($QUEST[3])) {
            //main page

            if (isset($_SESSION['QUEST3'])) {
                $QUEST[3] = $_SESSION['QUEST3'];
                unset($_SESSION['QUEST3']);
            } else {
                $QUEST[3] = '';
            }
            $setting_allowpage = ['account', 'ojacct', 'myboard', 'mycodepad', 'profile'];
            $_E['template']['setting']['defaultpage'] = 'profile';
            if (in_array($QUEST[3], $setting_allowpage)) {
                $_E['template']['setting']['defaultpage'] = $QUEST[3];
            }
            Render::renderSingleTemplate('user_setting', 'user');
            exit(0);
        }

        switch ($QUEST[3]) {
//Sub page of setting

            case 'profile':
                $viewdata = $userInfo->load_data('view');
                $_E['template'] = array_merge($_E['template'], $viewdata);
                userControl::registertoken('EDIT', 3600);
                Render::renderSingleTemplate('user_data_modify_profile', 'user');
                exit(0);
                break;
            case 'account':
                $viewdata = $userInfo->load_data('account');
                $_E['template']['acct'] = $viewdata;
                userControl::registertoken('EDIT', 3600);
                Render::renderSingleTemplate('user_data_modify_account', 'user');
                exit(0);
                break;
            case 'ojacct':
                userControl::registertoken('EDIT', 3600);
                page_ojacct($showid);
                Render::renderSingleTemplate('user_data_modify_ojacct', 'user');
                exit(0);
                break;
            case 'myboard':
                //WAIT FOR PRESYSTEM
                $statsboard = DB::tname('statsboard');
                $res = DB::query("SELECT `id`,`name` FROM `$statsboard` WHERE `owner` = '$showid'");
                $rowdata = [];
                if (!$res) {
                    $_E['template']['message'] = 'SQL Error...';
                    Render::renderSingleTemplate('common_message', 'common');
                    exit(0);
                }
                while ($data = DB::fetch($res)) {
                    $rowdata[] = $data;
                }
                $_E['template']['row'] = $rowdata;
                Render::renderSingleTemplate('user_data_modify_myboard', 'user');
                exit(0);
                break;
            case 'mycodepad':
                //WAIT FOR PRESYSTEM
                $codepad = DB::tname('codepad');
                if (userControl::isAdmin($showid) && $showid == $_G['uid']) {
                    $res = DB::query("SELECT `id`,`hash`,`timestamp` FROM `$codepad`");
                } else {
                    $res = DB::query("SELECT `id`,`hash`,`timestamp` FROM `$codepad` WHERE `owner` = '$showid'");
                }

                $rowdata = [];
                if (!$res) {
                    $_E['template']['message'] = 'SQL Error...';
                    Render::renderSingleTemplate('common_message', 'common');
                    exit(0);
                }
                while ($data = DB::fetch($res)) {
                    $rowdata[] = $data;
                }
                $_E['template']['row'] = $rowdata;
                Render::renderSingleTemplate('user_data_modify_mycodepad', 'user');
                exit(0);
                break;
            }
        break; //End of Setting
}

Render::renderSingleTemplate('nonedefined');
exit(0);
