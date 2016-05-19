<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'class_zjcore.inc.php';
class class_hdu
{
    public $version = '1.0';
    public $name = 'HDU capturer';
    public $description = 'HDU Online Judge capturer';
    public $copyright = 'by xiplus';
    public $pattern = '/^hdu[0-9]+$/i';
    private $zjcore;
    private $html;

    public function __construct()
    {
        $this->zjcore = new zjcore();
        $this->zjcore->websiteurl = 'http://acm.hdu.edu.cn/';
        $this->zjcore->userpage = 'userstatus.php?user=';
        $this->zjcore->classname = 'class_hdu';
    }

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_hdu','杭州電子科技大學HDU','Account Name',1)");
    }

    public function checkid($id)
    {
        return $this->zjcore->checkid($id);
    }

    public function preprocess($userlist, $problems)
    {
        global $_E;
        $this->zjcore->preprocess($userlist, $problems);
        $this->html = $this->zjcore->html_sumary;
    }

    public function query($uid, $pid)
    {
        global $_E;
        $pid = preg_replace('/[^0-9]+/', '', $pid);
        $response = $this->html[$uid];
        if (!$response) {
            return 0;
        }
        $start = strpos($response, '<p align=left><script language=javascript>') + 42;
        $end = strrpos($response, '</script><br></p>');
        $html = explode('</script><br></p>', substr($response, $start, $end - $start));

        $HDU_stats = 0;
        if (strpos($html[0], $pid) !== false) {
            $HDU_stats = 90;
        } elseif (strpos($html[1], $pid) !== false) {
            $HDU_stats = 70;
        } else {
            $HDU_stats = 0;
        }

        return $HDU_stats;
    }

    public function showname($pid)
    {
        $pid = preg_replace('/[^0-9]+/', '', $pid);

        return "<a href='http://acm.hdu.edu.cn/showproblem.php?pid=$pid' target='_blank'>HDU $pid</a>";
    }

    public function challink($uid, $pid)
    {
        $pid = preg_replace('/[^0-9]+/', '', $pid);

        return "http://acm.hdu.edu.cn/status.php?user=$uid&pid=$pid";
    }
}
