<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class userControl
{
    //Cookie Functions
    public static function SetCookie(string $name, string $value, int $expire = 0)
    {
        global $_config,$_E;

        return setcookie($_config['cookie']['namepre'].'_'.$name, $value, $expire, $_E['SITEDIR'], '', false, true);
    }

    public static function RemoveCookie(string $name)
    {
        return self::SetCookie($name, '');
    }

    public static function isCookieSet(string $name)
    {
        global $_config;

        return isset($_COOKIE[$_config['cookie']['namepre'].'_'.$name]);
    }

    public static function GetCookie(string $name)
    {
        global $_config;
        if (self::isCookieSet($name)) {
            return $_COOKIE[$_config['cookie']['namepre'].'_'.$name];
        }

        return false;
    }

    //Set a Token to Cookie and return Token
    public static function RegisterToken(string $namespace, int $timeleft)
    {
        global $_G,$_E,$_config;

        if ($_G['uid'] == 0) {
            self::SetCookie('uid', '0', time() + 3600);
        }

        $token = GenerateRandomString(TOKEN_LEN);
        $timeout = time() + $timeleft;

        $_SESSION[$namespace]['token'] = $token;
        $_SESSION[$namespace]['timeout'] = $timeout;
        $_SESSION[$namespace]['uid'] = $_G['uid'];
        self::SetCookie($namespace, $token, $timeout);
        LOG::msg(Level::Debug, "Reg Token [$namespace]$token");

        return $token;
    }

    public static function DeleteToken(string $namespace)
    {
        if (self::isCookieSet($namespace)) {
            self::RemoveCookie($namespace);
        }
        if (isset($_SESSION[$namespace])) {
            unset($_SESSION[$namespace]);
        }
    }

    //bool userControl::checktoken(namespace)
    //if function return true ,it mean two things:
    //1.$_COOKIE[$_config['cookie']['namepre'].'_uid'] is leagl
    //2.token $namespace is leagl
    public static function CheckToken(string $namespace)
    {
        global $_G,$_config;
        if (!self::isCookieSet($namespace) || !self::isCookieSet('uid')) {
            return false;
        }

        $token = self::GetCookie($namespace);
        $uid = self::GetCookie('uid');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $token) ||
            !preg_match('/^[0-9]+$/', $uid)) {
            return false;
        }

        if (isset($_SESSION[$namespace])) {
            if ($_SESSION[$namespace]['uid']  == $uid &&
                $_SESSION[$namespace]['token'] == $token &&
                time() < $_SESSION[$namespace]['timeout']) {
                return true;
            }
        }

        return false;
    }

    //userControl::intro()
    //this function must call first to check if user has logined and set var $_G
    public static function intro()
    {
        global $_G,$permission,$_config;
        $acctable = DB::tname('account');
        if (self::CheckToken('login')) {
            //load user data
            //$_COOKIE[$_config['cookie']['namepre'].'_uid'] is checked in userControl::checktoken
            $loginuid = self::GetCookie('uid');

            if ($sqldata = DB::fetch("SELECT * FROM  `$acctable` ".
                                     'WHERE `uid` = ?', [$loginuid])) {
                $_G = $sqldata;
            } else {
                LOG::msg(Level::Error, 'Caonnot Load login info from DB', $loginuid);
                $_G = $permission['guest'];
            }
        } else {
            // guest

            self::DeleteToken('login');
            $_G = $permission['guest'];
        }
    }

    public static function SetLoginToken($uid)
    {
        global $_G,$_E,$_config;
        $acctable = DB::tname('account');

        if ($sqldata = DB::fetchEx("SELECT * FROM  `$acctable` ".
                                    'WHERE `uid` = ? ', $uid)) {
            $_G['uid'] = $uid;
            self::RegisterToken('login', 864000);
            self::SetCookie('uid', $uid, time() + 864000);

            return true;
        } else {
            return false;
        }
    }

    public static function DelLoginToken()
    {
        global $_G;
        self::DeleteToken('login');
    }

    public static function getuserdata($table, $uid = null)
    {
        $table = DB::tname($table);
    }

    public static function getpermission($uid)
    {
        global $_G,$_E,$_config;
        if ($uid == -1) {
            return false;
        }
        if ($uid == $_G['uid']) {
            return true;
        }
        if (in_array($_G['uid'], $_E['site']['admin'])) {
            return true;
        }

        return false;
    }

    public static function isAdmin($uid = null)
    {
        global $_G,$_E,$_config;
        if ($uid === null) {
            return in_array($_G['uid'], $_E['site']['admin']);
        }

        return in_array($uid, $_E['site']['admin']);
    }
}
