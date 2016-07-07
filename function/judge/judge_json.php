<?php

class json_main
{
    public $chal_id;
    public $code_path;
    public $res_path;
    public $comp_type;
    public $check_type;
    public $metadata;
    public $test = [];
}

class json_test
{
    public $test_idx;
    public $timelimit;
    public $memlimit;
    public $metadata;
}

class json_testdata
{
    public $data = [];
}

class json_chalmeta
{
    public $redir_test;
    public $redir_check;
}

class json_redir_test
{
    public $testin;
    public $testout;
    public $pipein;
    public $pipeout;
}

class json_redir_check
{
    public $testin;
    public $ansin;
    public $pipein;
    public $pipeout;
}

class json_result
{
    public $chal_id;
    public $uid;
    public $verdict;
    public $state;
    public $result = [];
    public $score;
}

class json_resultdata
{
    public $test_idx;
    public $state;
    public $runtime;
    public $peakmem;
    public $verdict;
}
