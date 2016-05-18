<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class zjcore
{
    public $websiteurl;
    public $classname;
    public $userpage = 'UserStatistic?account=';
    public $userpagepattern = '$WEB.$USERPAGE.$user';
    public $html_sumary = [];

    public function checkid($id)
    {
        return preg_match('/^[0-9a-zA-Z_\-]+$/', $id);
    }

    public function preprocess($userlist, $problems)
    {
        global $_E;
        $WEB = $this->websiteurl;
        $USERPAGE = $this->userpage;
        foreach ($userlist as $user) {
            if (!$this->checkid($user)) {
                continue;
            }
            if ($res = DB::loadcache($this->classname."_$user")) {
                //.....
            } else {
                //$res = @file_get_contents($this->websiteurl.$this->userpage.$user);
                $url = '';
                if (eval('$url='.$this->userpagepattern.';') !== false) {
                    $res = @file_get_contents($url);
                    //echo($url);
                    $res = str_replace(["\r\n", "\t", '  '], '', $res);
                    DB::putcache($this->classname."_$user", $res, 10);
                }
            }

            $this->html_sumary[$user] = $res;
        }
    }

    //pid real!
    public function query($uid, $pid)
    {
        global $_E;

        $ZJ_stats = 0;

        $response = $this->html_sumary[$uid];
        if (!$response) {
            return 0;
        }
        if (!(strrpos($response, 'DataException') === false)) {
            return 0;
        }

        $start = strpos($response, '?problemid='.$pid);
        $end = strpos($response, '>'.$pid.'</a>');
        $html = substr($response, $start, $end - $start);

        if (strpos($html, '"acstyle"')) {
            $ZJ_stats = 90;
        } elseif (strpos($html, 'color: #666666; font-weight: bold;')) {
            $ZJ_stats = 70;
        } elseif (strpos($html, 'color: #666666')) {
            $ZJ_stats = 0;
        } else {
            //THROW ERROR
        }

        return $ZJ_stats;
    }

    public function reg_problemid($pid)
    {
        if (!preg_match('/.*:([a-zA-Z]{1})(\d+)/', $pid, $match)) {
            return false;
        }
        $word = $match[1];
        $num = $match[2];
        $word = strtolower($word);
        $num = str_pad($num, 3, '0', STR_PAD_LEFT);

        return $word.$num;
    }
}
