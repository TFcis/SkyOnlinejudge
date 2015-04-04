<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

function passwordHash($resoure)
{
    $re = md5("ncid".md5($resoure));
    //$re = password_hash($resoure, PASSWORD_BCRYPT);
    return $re;
}
function getTimestamp()
{
    return date('Y-m-d G:i:s');
}

define('NOE_FAIL',0);
define('NOE_IS_NICKNAMEL',1);
define('NOE_IS_EMAI',2);

function checkpassword($pass)
{
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    if( !is_string($pass) )
        return false;
    return preg_match($pattern,$pass);
}
function checknickname($name)
{
    if( $name !== addslashes($name) )
        return false;
    if( strpos($name,'@') !== false )
        return false;
    return true;
}
function register($email,$nickname,$password,$repeat)
{
    global $_E;
    global $_config;
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    
    $_E['template']['reg'] = array();
    $acctable = DB::tname('account');
    $timestamp = getTimestamp();
    $sqlres;
    
    if( !preg_match($pattern,$email) || !checkpassword($password) || $password!= $repeat ||
        !checknickname($nickname))
    {
        //use language!
        $_E['template']['reg'] = '格式錯誤';
        return false;
    }
    
    $nickname = addslashes($nickname);
    $password = passwordHash($password);

    $sqlres = DB::query(  "SELECT * FROM  `$acctable`".
                            "WHERE  `email` =  '$email'");
    if(DB::fetch($sqlres))
    {
        $_E['template']['reg'] = '帳號已被註冊';
        return false;
    }
    if(!DB::query("INSERT INTO `$acctable` ".
                    "(`uid`, `email`, `passhash`, `nickname`, `timestamp`) ".
                    "VALUES (NULL, '$email', '$password', '$nickname', '$timestamp')"))
    {
        $_E['template']['reg'] = 'SQL error';
        return false;
    }
    return true;
}

function login($email,$password)
{
    global $_E;
    $pattern  = '/^[._@a-zA-Z0-9]{3,30}$/';
    
    $_E['template']['login'] = array();
    $acctable = DB::tname('account');
    $sqlres;
    $userdata = null;
    $resultdata = array(false,'');
    if( checknickname($email) )
    {
        $nick = $email;
        if($nick !== addslashes($nick))
        {
            $resultdata[1] = '格式錯誤';
            return $resultdata;
        }
        $sqlres=DB::query("SELECT `email` FROM  `$acctable` ".
                            "WHERE  `nickname` =  '$nick' ");
        if($res = DB::fetch($sqlres) )
        {
            $email = $res['email'];
        }
        else
        {
            $resultdata[1] = '無此暱稱';
            return $resultdata;
        }
    }
    if( !preg_match($pattern,$email) || !checkpassword($password) )
    {
        $resultdata[1] = '帳密錯誤';
        return $resultdata;
    }

    $sqlres=DB::query("SELECT * FROM  `$acctable`".
                        "WHERE  `email` =  '$email'");
    if(! ($userdata = DB::fetch($sqlres)) )
    {
        $resultdata[1] = '無此帳號';
        return $resultdata;
    }
    if( passwordHash($password)!=$userdata['passhash'] )
    {
        $resultdata[1] = '密碼錯誤';
        return $resultdata;
    }
    $resultdata[0]=true;
    $resultdata[1]=$userdata;
    return $resultdata;
}

function page_ojacct($uid)
{
    global $_E;
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
    $table_oj = DB::tname('ojlist');
    $_E['template']['oj']=array();
    
    if( !isset($_E['ojlist']) )
        if( !envadd('ojlist') )
            return false;
            
    $userdata = new UserInfo($uid);
    $userojacctlist = $userdata -> load_data('ojacct');

    foreach($_E['ojlist'] as $oj)
    {
        $tmp = $oj;
        $tmp['info'] = '';
        $tmp['user'] = $userojacctlist[ $oj['class'] ];
        $tmp['c'] = $class[ $oj['class'] ];
        if( $tmp['user']['account'] )
        {
            if( $tmp['user']['approve'] == 0 ) // No Check
            {
                $tmp['info'] = '尚未認證' ;
            }
            else
            {
                if( method_exists( $class[ $oj['class'] ] , 'account_detail' ) )
                {
                    $tmp['info'] = $class[ $oj['class'] ]->account_detail($tmp['user']['account']);
                    if( !$tmp['info'] ) $tmp['info'] = '';
                    $tmp['info'] = "已驗證 ".$tmp['info'];
                }
            }
        }
        $_E['template']['oj'][] = $tmp;
    }
    
    return true;
}

function modify_ojacct($argv,$euid)
{
    global $_E;
    $table = DB::tname('userojlist');
    if( !isset($_E['ojlist']) )
    {
        envadd('ojlist');
    }
    $class = Plugin::loadClassByPluginsFolder('rank/board_other_oj');
    
    $userdata = new UserInfo($euid);
    $uacct = $userdata -> load_data('ojacct');
    
    foreach($argv as $oj => $acct)
    {
        if( !empty($acct) )
        {
            if( $uacct[$oj]['approve'] ==0 && $class[$oj]->checkid($acct) )
            {
                $uacct[$oj]['account'] = $acct;
                $uacct[$oj]['approve']=0;
            }
            else
            {
                return array(false,"Accout error :$oj");
            }
        }
    }
    if( $userdata -> save_data('ojacct',$uacct) )
        return array(true);
    return array(false,'SQL ERROR');
}

function getgravatarlink($email,$size = null)
{
    if( !is_string($email) || !is_numeric($size) && $size !== null )
        return '';
    $email = md5( strtolower( trim( $email ) ) );
    $res = "http://www.gravatar.com/avatar/$email?";
    
    #check
    $check = $res."d=404";
    $header = get_headers($check);
    if( $header[0] == "HTTP/1.0 404 Not Found" )
        $res = "http://www.gravatar.com/avatar/$email?d=identicon&";

    if( isset($size) )
        $res .= "?s=$size";
    return $res;
}

class UserInfo
{
    private $uid;
    private $data;
    function __construct( $_uid = 0 , $debug = false )
    {
        if( is_numeric($_uid) )
        {
            $acceptflag = true;
            $uid = (int)$_uid;
            
            #guest
            if( $uid ===0 ){
                $acceptflag = false;
            }
            #registed user
            $acctdata = DB::getuserdata( 'account',$uid );
            if( $acctdata === false || !isset( $acctdata[$uid]) ){
                $acceptflag = false;
            }

            if($acceptflag)
            {
                $this->uid = $uid;
                $this->data['account'] = $acctdata[$uid];
            }
            else
            {
                $this->data['account'] = null;
                if( $uid === 0)
                    $this->uid = 0;
                else
                    $this->uid = -1;
            }
        }
        else
        {
            if( $debug )
                die('construct error : type error');
            $this->uid = -1;
        }
    }
    function is_registed(){
        return $this->uid > 0;
    }
    function is_guest(){
        return $this->uid === 0;
    }
    function is_load(){
        return $this->uid !== -1;
    }
    
    private function _load_data($name)
    {
        $method = "_load_data_$name";
        if( method_exists(get_class(),$method) )
            if( $data = $this->$method() )
                return $this->data[$name]=$data;
        return false;
    }
    
    private function _load_data_view()
    {
        $res = DB::getuserdata('profile',$this->uid);
        if( isset($res[$this->uid]) )
        {
            $res = $res[$this->uid];
        }
        else
        {
            
            $res = array();
            $p = rand(1,5);
            switch($p)
            {
                case 1:
                    $res['quote'] = 'The value of a man resides in what he gives and not in what he is capable of receiving.';
                    $res['quote_ref'] =  'Albert Einstein';
                    break;
                case 2:
                    $res['quote'] = 'In the End, we will remember not the words of our enemies, but the silence of our friends.';
                    $res['quote_ref'] = 'Martin Luther King, Jr.';
                    break;
                case 3:
                    $res['quote'] = 'If you shed tears when you miss the sun, you also miss the stars.';
                    $res['quote_ref'] = 'Robíndronath Thakur';
                    break;
                case 4:
                    $res['quote'] = 'Histories make men wise ; poems witty; the mathematics subtle; natural philosophy deep ; moral grave ; logic and rhetoric able to contend.';
                    $res['quote_ref'] = 'Francis Bacon';
                    break;
                default:
                    $res['quote'] =  "A man provided with paper, pencil, and rubber, and subject to strict discipline, is in effect a universal Turing Machine.";
                    $res['quote_ref'] = 'Alan Mathison Turing';
                    break;
            }
            $res['avaterurl'] = '';
            $res['backgroundurl'] = 'http://i.imgur.com/n2EOWhO.jpg';
            $this->_save_data_view($res);
        }
        $res['quote'] = htmlspecialchars($res['quote']);
        $res['quote_ref'] = htmlspecialchars($res['quote_ref']);
        $res['nickname'] = $this->data['account']['nickname'];
        
        return $res;
    }
    private function _save_data_view( $viewdata , $cg = null)
    {
        $tprofile = DB::tname('profile');
        if( !isset($viewdata['quote']) ) $viewdata['quote'] = '';
        if( !isset($viewdata['quote_ref']))$viewdata['quote_ref'] = '';
        if( !isset($viewdata['backgroundurl'])) $viewdata['backgroundurl'] = '';
        if( !isset($viewdata['avatarurl'])) $viewdata['avatarurl'] = '';
        
        $quote = DB::real_escape_string($viewdata['quote']);
        $quote_ref = DB::real_escape_string($viewdata['quote_ref']);
        $backgroundurl = DB::real_escape_string($viewdata['backgroundurl']);
        $avatarurl = DB::real_escape_string($viewdata['avatarurl']);
        
        $uid = $this->uid;
        $res = DB::query("INSERT INTO `$tprofile` (`uid`, `quote`, `quote_ref`, `avatarurl`, `backgroundurl`)
                                    VALUES ($uid,'$quote','$quote_ref','$avatarurl','$backgroundurl')
                                    ON DUPLICATE KEY
                                    UPDATE  `quote` = '$quote',
                                            `quote_ref` = '$quote_ref',
                                            `avatarurl`='$avatarurl',
                                            `backgroundurl`='$backgroundurl'");
        if( $res === false )
            throw new Exception('error');
        return true;
    }
    
    private function _load_data_ojacct()
    {
        $userojaccttable = DB::tname('userojacct');
        $res = DB::query("SELECT * FROM `$userojaccttable` WHERE `uid` = ".$this->uid);
        if( !$res ) return false;
        $val = array();
        while( $tmp = DB::fetch($res) )
        {
            $val[] = $tmp;
        }
        $flag = false;
        $res = ojacct_reg($val,$this->uid,$flag);
        if( $flag )
        {
            $this->_save_data_ojacct($res);
        }
        Render::errormessage($flag);
        return $res;
    }
    private function _save_data_ojacct( $ojarray , $cg = null )
    {
        $userojaccttable = DB::tname('userojacct');
        //remove old data
        if( isset($cg) )
        {
            if(is_array($cg[0]))
            {
                DB::syslog('RM'.$cg[0],'ojacct');
                foreach($cg[0] as $indexid)
                    DB::query("DELETE FROM `$userojaccttable` WHERE `indexid` = '$indexid'");
            }
            if(is_array($cg[1]))
            {
                foreach($ojarray as $row)
                {
                    if( in_array( $row['indexid'] , $cg[1] ) )
                    {
                        DB::syslog('ADD'.$row['indexid'],'ojacct');
                        $uid = (int)$row['uid'];
                        $id  = (int)$row['id'];
                        $indexid = "$uid+$id";
                        $account = DB::real_escape_string($row['account']);
                        $approve = (int)$row['approve'];
                        DB::query("INSERT INTO `$userojaccttable` (`indexid`,`uid`,`id` ,`account`,`approve`)
                                    VALUES ('$indexid',  $uid,  $id,  '$account',  $approve)
                                    ON DUPLICATE KEY
                                    UPDATE `account` = '$account' , `approve` = $approve");
                    }
                }
            }
        }
        else
        {
            DB::syslog('GLOBAL ADD'.$this->uid,'ojacct');
            DB::query("DELETE FROM `$userojaccttable` WHERE `uid` = ".$this->uid);
            foreach( $ojarray as $row )
            {
                $uid = (int)$row['uid'];
                $id  = (int)$row['id'];
                $indexid = "$uid+$id";
                $account = DB::real_escape_string($row['account']);
                $approve = (int)$row['approve'];
                DB::query("INSERT INTO `$userojaccttable` (`indexid`,`uid`,`id` ,`account`,`approve`)
                            VALUES ('$indexid',  $uid,  $id,  '$account',  '$approve')");
            }
        }
        return true;
    }

    function load_data($dataname)
    {
        $res = null;
        #account is available when class constructed
        $available_argvs = array('account','view','ojacct');
        
        if(!in_array($dataname,$available_argvs))
            return null;
            
        if(!isset($this->data[$dataname]))
            return $this->_load_data($dataname);
        return $this->data[$dataname];
    }
    
    function save_data($dataname,$value, $args = null )
    {
        $available_argvs = array('ojacct','view');
        
        if(!in_array($dataname,$available_argvs))
            return false;
        try
        {
            $method = "_save_data_$dataname";
            if( method_exists(get_class(),$method) )
            {
                $this->$method($value,$args);
            }
        }
        catch (Exception $e)
        {
            return false;
        }
        return true;
    }
    function account($s)
    {
        return $this->data['account'][$s];
    }
}