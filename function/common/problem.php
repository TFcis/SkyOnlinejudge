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
    public function __construct(int $_pid)
    {
        $tproblem = DB::tname('problem');
        $data = DB::fetchEx("SELECT * FROM `$tproblem` WHERE `pid`=?",$_pid);
        if( $data == false ){
            throw new Exception("SQL Error");
        }
    }
};