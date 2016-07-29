<?php namespace SKYOJ\Problem;

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}
require_once 'function/common/problem.php';

class ProblemDescriptionEnum extends \SKYOJ\BasicEnum
{
    const MarkDown = 1;
    const HTML = 2;
    const PDF = 3;
}