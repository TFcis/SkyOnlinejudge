<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'function/common/problem.php';

//TODO PHP7.1 use ?string
function CreateNewProblemID(int $owner,string $title):int
{
    $tproblem = \DB::tname('problem');
    $res = \DB::queryEx("INSERT INTO `{$tproblem}` (`pid`, `owner`, `title`) 
                         VALUES (NULL,?,?)",$owner,$title);
    return $res?\DB::lastInsertId('pid'):null;
}
