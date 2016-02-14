<?php
/*
 * Log Core
 * 2016 Sky Online Judge Project
 */

include("../GlobalSetting.php");

class LOG
{
    private static $IsIntroed = false;
    static $log_setting;
    static function intro(){
        global $_E;
        //fucking dzing zz
        /*self::$h_output_file = @fopen($_E['logsys']['logfile'],'a');
        if( self::$h_output_file === false ){
            die('Cannot Create Log File! Please Check Setting: logsys/logfile');
        }*/
        self::$log_setting = $_E['logsys'];
        self::$IsIntroed = true;
    }
    
    //Just check if LOG::intro called
    static function IsIntro(){
        return self::$IsIntroed === true;
    }
    
    //check if LOG::intro called
    //if $IsIntroed === false it will call die()
    static function CheckIntro(){
        if( !LOG::IsIntro() ){
            die('Call LOG::intro() before using LOG SYS');
        } 
    }
    
    //Return current MYSQL timestamp
    static function TimeStamp(){
        return date("Y-m-d H:i:s");
    }
    
    static function GenerateLogMessage($called_info,$message,$endline)
    {
        
    }
    
    //Uh... function log() is considered a constructor of class LOG
    //So we use msg() to instead of it
    ///no return value with this function
    static function msg($level,$message,...$dumpvals)
    {
        LOG::CheckIntro();
        $debug_info = debug_backtrace();
        $called_info = array_shift($debug_info);
        
        $str_output = "[". LOG::TimeStamp() ."][{$called_info['file']}:{$called_info['line']}] ".$message.PHP_EOL;
        if( !empty($dumpvals) )
        {
             $str_output.= 
                "===var_dump===".PHP_EOL;
            foreach( $dumpvals as $v )
            {
                $str_output.=
                var_export($v,true).PHP_EOL.
                "==============".PHP_EOL;
            }
        }
        
        if( self::$log_setting['msgshower']['enabled'] ) 
        {
            $ip  = self::$log_setting['msgshower']['ip'];
            $port= self::$log_setting['msgshower']['port'];
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if( @socket_connect($socket,$ip,$port) )
            {
                $sendstr     = $str_output;
                $real_strlen = strlen($sendstr);
                while(true)
                {
                    $len = socket_write($socket,$sendstr,$real_strlen);
                    if( $len === false ) break;
                    if( $len < $real_strlen )
                    {
                        $sendstr = substr($sendstr, $len);
                        $real_strlen -= $len;
                    } 
                    else
                    {
                        break;
                    }
                }
            }
            else
            {
                die('Please Check MsgShower Server');
            }
            socket_close($socket);
        }
        //DEBUG
        $str_output = str_replace("\t","    ",$str_output);
        $str_output = str_replace(" ","&nbsp;",$str_output);
        echo nl2br($str_output);
    }
}

LOG::intro();
LOG::msg("LEVEL","Test Info",array(1,"test dump"),15);
LOG::msg("LEVEL","Test Info");
LOG::msg("LEVEL","Test Info2");