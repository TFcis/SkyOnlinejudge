<?php namespace SkyOJ\Challenge;

/*public $taskid; //subid
public $runtime;//ms
public $mem;    //in KB
public $state;  //AC WA..
public $score;  //sub score
public $msg;    //judge message
*/

class Package
{
    public $id;
    public $runtime;
    public $memory;
    public $result_code;
    public $message;
    public $score;
}

class Result
{
    public $tasks = [];
    public $score = [];

    public function _score()
    {
        $this->score = $this->tasks;

        foreach( $this->score as &$row )
        {
            $row->score = 0;
            if( $row->result_code == ResultCode::AC )
                $row->score = 100;
        }
    }
}