<?php
/*
 * problem
 * 2016 Sky Online Judge Project
 * By LFsWang
 * 
 */
/*
Storge format
SQL : id,phash,status
data/problem/prased.html
*/
require_once($_E['ROOT'].'/function/externals/Parsedown.php');

class Problem{
    private $pid;
    private $phash;
    public static function CreateDefault(int $pid)
    {
        global $_E;
        $path = $_E['problem']['path'];
        if( !mkdir($path."$pid/",644) )return false;
        if( !mkdir($path."$pid/testdata/",644) )return false;
        if( !mkdir($path."$pid/attach/",644) )return false;
        return true;
    }
    
    public function __construct(int $_pid)
    {
        $tproblem = DB::tname('problem');
        $data = DB::fetchEx("SELECT * FROM `$tproblem` WHERE `pid`=?",$_pid);
        if( $data == false ){
            throw new Exception("SQL Error");
        }
    }
};