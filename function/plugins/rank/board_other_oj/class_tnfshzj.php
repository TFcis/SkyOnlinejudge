<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'class_zjcore.inc.php';
class class_tnfshzj
{
    public $version = '1.0';
    public $name = 'TNFSJ ZJ capturer';
    public $description = 'TNFSJ Zerojudge capturer';
    public $copyright = 'by Sylveon';
    public $pattern = '/^tnfshzj:[a-z]{1}[0-9]+$/';
    private $zjcore;

    public function __construct()
    {
        $this->zjcore = new zjcore();
        $this->zjcore->websiteurl = 'http://judge.tnfsh.tn.edu.tw:8080/';
        $this->zjcore->classname = 'class_tnfshzj';
        $this->zjcore->userpage = 'ShowUserStatistic?account=';
    }

    public function install()
    {
        $tb = DB::tname('ojlist');
        DB::query("INSERT INTO `$tb`
	            (`id`, `class`, `name`, `description`, `available`) VALUES
	            (NULL,'class_tnfshzj','台南一中ZJ','Account Name',1)");
    }

    public function checkid($id)
    {
        return $this->zjcore->checkid($id);
    }

    public function preprocess($userlist, $problems)
    {
        global $_E;
        $this->zjcore->preprocess($userlist, $problems);
    }

    public function query($uid, $pid)
    {
        global $_E;
        $pid = $this->zjcore->reg_problemid($pid);

        return $this->zjcore->query($uid, $pid);
    }

    public function showname($pid)
    {
        $pname = $this->zjcore->reg_problemid($pid);

        return "<a href='http://judge.tnfsh.tn.edu.tw:8080/ShowProblem?problemid=$pname' target='_blank'>一中 $pname</a>";
    }

    public function challink($uid, $pid)
    {
        $pname = $this->zjcore->reg_problemid($pid);

        return 'http://judge.tnfsh.tn.edu.tw:8080/RealtimeStatus?problemid='.$pname.'&account='.$uid;
    }
}
