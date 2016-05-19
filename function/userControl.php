<?php
if(!defined('IN_SKYOJSYSTEM'))
{
  exit('Access denied');
}

class userControl
{
    //Cookie Functions
    static function SetCookie(string $name,string $value,int $expire = 0)
    {
        global $_config,$_E;     
        return setcookie($_config['cookie']['namepre'].'_'.$name,$value,$expire,$_E['SITEDIR'],'',false,true);
    }
    
    static function RemoveCookie(string $name)
    {
        return userControl::SetCookie($name,'');
    }
    
    static function isCookieSet(string $name)
    {
        global $_config;
        return isset($_COOKIE[$_config['cookie']['namepre'].'_'.$name]);
    }
    
    static function GetCookie(string $name)
    {
        global $_config;
        if( userControl::isCookieSet($name) )
            return $_COOKIE[$_config['cookie']['namepre'].'_'.$name];
        return false;
    }
    //Set a Token to Cookie and return Token
    static function RegisterToken(string $namespace,int $timeleft)
    {
        global $_G,$_E,$_config;
        
        if( $_G['uid']==0 )
        {
            userControl::SetCookie('uid','0',time()+3600);
        }
        
        $token = GenerateRandomString(TOKEN_LEN);
        $timeout = time() + $timeleft;
        
        $_SESSION[$namespace]['token']      = $token;
        $_SESSION[$namespace]['timeout']    = $timeout;
        $_SESSION[$namespace]['uid']        = $_G['uid'];
        userControl::SetCookie($namespace,$token,$timeout);
        LOG::msg(Level::Debug,"Reg Token [$namespace]$token");
        return $token;
    }
    
    static function DeleteToken(string $namespace)
    {
        if( userControl::isCookieSet($namespace) )
            userControl::RemoveCookie($namespace);
        if( isset($_SESSION[$namespace]) )
        {
            unset($_SESSION[$namespace]);
        }
    }
    
    #bool userControl::checktoken(namespace)
    #if function return true ,it mean two things:
    #1.$_COOKIE[$_config['cookie']['namepre'].'_uid'] is leagl
    #2.token $namespace is leagl
    static function CheckToken(string $namespace)
    {
        global $_G,$_config;
        if( !userControl::isCookieSet($namespace) || !userControl::isCookieSet('uid') )
        {
            return false;
        }
        
        $token = userControl::GetCookie($namespace);
        $uid   = userControl::GetCookie('uid');
        
        if( !preg_match('/^[a-zA-Z0-9]+$/',$token) ||
            !preg_match('/^[0-9]+$/',$uid))
        {
            return false;
        }
        
        if( isset($_SESSION[$namespace]) )
        {
            if( $_SESSION[$namespace]['uid']  == $uid && 
                $_SESSION[$namespace]['token']== $token &&
                time() < $_SESSION[$namespace]['timeout'] )
                return true;
        }
        return false;
    }
    
    #userControl::intro()
    #this function must call first to check if user has logined and set var $_G
    static function intro()
    {
        global $_G,$permission,$_config;
        $acctable = DB::tname('account');
        if( userControl::CheckToken('login') )
        {
            //load user data
            //$_COOKIE[$_config['cookie']['namepre'].'_uid'] is checked in userControl::checktoken
            $loginuid = userControl::GetCookie('uid');

            if( $sqldata = DB::fetch("SELECT * FROM  `$acctable` ".
                                     "WHERE `uid` = ?",array($loginuid)) )
            {
                $_G = $sqldata;
            }
            else
            {
                LOG::msg(Level::Error,"Caonnot Load login info from DB",$loginuid);
                $_G = $permission['guest'];
            }
        }
        else // guest
        {
            userControl::DeleteToken('login');
            $_G = $permission['guest'];
        }
    }
    
    static function SetLoginToken($uid)
    {
        global $_G,$_E,$_config;
        $acctable = DB::tname('account');
        
        if( $sqldata = DB::fetchEx("SELECT * FROM  `$acctable` ".
                                    "WHERE `uid` = ? ",$uid) )
        {
            $_G['uid'] = $uid;
            userControl::RegisterToken('login',864000);
            userControl::SetCookie('uid',$uid,time()+864000);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    static function DelLoginToken()
    {
        global $_G;
        userControl::DeleteToken('login');
    }
    
    static function getuserdata( $table ,$uid = [] )
    {
        $table = DB::tname($table);
        $userdata=[];
        foreach($uid as $u)
        {
            $u=(string)$u;
            $pdo=DB::prepare("SELECT * FROM `$table` WHERE `uid` = ?");
            if(DB::execute($pdo,array($u)))
            {
                $data=$pdo->fetchAll();
                $userdata[$u]=$data[0];
           }	
        }
        LOG::msg(Level::Debug,"",$userdata);
        return $userdata;
    }
    
    static function getpermission($uid)
    {
        global $_G,$_E,$_config;
        if( $uid == -1 )
            return false;
        if( $uid == $_G['uid'])
            return true;
        if(in_array($_G['uid'],$_E['site']['admin']))
            return true;
        return false;
    }
    static function isAdmin( $uid = null )
    {
        global $_G,$_E,$_config;
        if($uid === null)
            return in_array($_G['uid'],$_E['site']['admin']);
        return in_array($uid,$_E['site']['admin']);
    }
}
