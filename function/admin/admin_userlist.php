<?php namespace SKYOJ\Admin;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

require_once $_E['ROOT'].'/function/common/pagelist.php';
use  \SKYOJ\PageList;
function userlistHandle(){
    global $_E;

    $page = (int)\SKYOJ\safe_get('page');
    $_E['template']['userlist'] = [];
    $pl = new PageList('account');
    $data = $pl->GetPageDataByPage($page,'uid','*');
    if ($data !== false) {
        $_E['template']['userlist'] = $data;
        $_E['template']['userlist_page_list'] = $pl;
        $_E['template']['userlist_now'] = $page;
    }
    \Render::renderSingleTemplate('admin_userlist', 'admin');
}
