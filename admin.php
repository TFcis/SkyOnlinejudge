<?php

require_once 'GlobalSetting.php';
require_once 'function/SkyOJ.php';

if (!userControl::isAdmin()) {
    header('Location: index.php');
    exit(0);
}
$allowmod = ['plugins', 'log'];
if (Quest(0)) {
    if (!userControl::CheckToken('ADMIN_CSRF', safe_post('_t'))) {
        Log::msg(Level::Warning, 'ADMIN CSRF Token error!');
        Render::renderSingleTemplate('nonedefined');
        exit(0);
    }
    $mod = Quest(0);
    if (!in_array($mod, $allowmod)) {
        Render::renderSingleTemplate('nonedefined');
        exit(0);
    } else {
        require_once $_E['ROOT'].'/function/admin/admin.lib.php';
        $funcpath = $_E['ROOT']."/function/admin/admin_$mod.php";
        if (file_exists($funcpath)) {
            require $funcpath;
        } else {
            Render::renderSingleTemplate("admin_$mod", 'admin');
        }
        exit(0);
    }
} else {
    $token = null;
    if (userControl::isToeknSet('ADMIN_CSRF') &&
        userControl::CheckToken('ADMIN_CSRF', userControl::getSavedToken('ADMIN_CSRF'))) {
        $token = userControl::getSavedToken('ADMIN_CSRF');
    } else {
        $token = userControl::RegisterToken('ADMIN_CSRF', 3600, false);
    }
    $_E['template']['ADMIN_CSRF'] = $token;
    $_E['template']['pluginfolders'] = Plugin::getAllFolders();
    Render::render('admin_main', 'admin');
    exit(0);
}
