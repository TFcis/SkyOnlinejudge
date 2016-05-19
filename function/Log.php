<?php
/*
 * Log Core
 * 2016 Sky Online Judge Project
 */
if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

final class Level
{
    const SQLERROR = -1;
    const Critical = 0;
    const Error = 1;
    const Warning = 2;
    const Notice = 3;
    const Debug = 4;
}

function LevelName(int $level):string
{
    static $LevelName = [];
    if (empty($LevelName)) {
        $classinfo = new ReflectionClass('Level');
        $cnt = $classinfo->getConstants();
        foreach ($cnt as $str => $val) {
            $LevelName[$val] = $str;
        }
    }
    if (!array_key_exists($level, $LevelName)) {
        die('No Such Level Name!');
    }

    return $LevelName[$level];
}

class LOG
{
    private static $IsIntroed = false;
    public static $log_setting;

    public static function intro()
    {
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
    public static function IsIntro()
    {
        return self::$IsIntroed === true;
    }

    //check if LOG::intro called
    //if $IsIntroed === false it will call die()
    public static function CheckIntro()
    {
        if (!self::IsIntro()) {
            die('Call LOG::intro() before using LOG SYS');
        }
    }

    //Return current MYSQL timestamp
    public static function TimeStamp()
    {
        return date('Y-m-d H:i:s');
    }

    public static function GenerateLogMessage($called_info, $message, $endline)
    {
    }

    //Uh... function log() is considered a constructor of class LOG
    //So we use msg() to instead of it
    ///no return value with this function
    public static function msg(int $level, string $message, ...$dumpvals)
    {
        self::CheckIntro();
        $check_sended = false;
        $timestamp = self::TimeStamp();

        $debug_info = debug_backtrace();
        $called_info = &$debug_info[0];

        $str_info = "[{$timestamp}][".LevelName($level).']';
        $str_output = "[{$called_info['file']}:{$called_info['line']}] ".$message.PHP_EOL;
        if (!empty($dumpvals)) {
            $str_output .=
                '===var_dump==='.PHP_EOL;
            foreach ($dumpvals as $v) {
                $str_output .=
                var_export($v, true).PHP_EOL.
                '=============='.PHP_EOL;
            }
        }

        if ($level != Level::Debug) {
            $syslog = DB::tname('syslog');
            if (DB::query("INSERT INTO `{$syslog}`(`id`, `timestamp`, `level`, `message`)
                           VALUES(NULL,?,?,?)", [$timestamp, $level, $str_output])) {
                $check_sended = true;
            }
        } else {
            $check_sended = true;
        }

        if (self::$log_setting['msgshower']['enabled']) {
            $ip = self::$log_setting['msgshower']['ip'];
            $port = self::$log_setting['msgshower']['port'];
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (@socket_connect($socket, $ip, $port)) {
                $sendstr = $str_info.$str_output;
                $real_strlen = strlen($sendstr);
                while (true) {
                    $len = socket_write($socket, $sendstr, $real_strlen);
                    if ($len === false) {
                        break;
                    }
                    if ($len < $real_strlen) {
                        $sendstr = substr($sendstr, $len);
                        $real_strlen -= $len;
                    } else {
                        break;
                    }
                }
                $check_sended = true;
            } else {
                die('Please Check MsgShower Server');
            }
            socket_close($socket);
        }

        if (!$check_sended) {
            die('Log Core Error! Cannot Save Log information');
            //$str_output = str_replace("\t","    ",$str_output);
            //$str_output = str_replace(" ","&nbsp;",$str_output);
            //echo nl2br($str_output);
        }
    }
}
