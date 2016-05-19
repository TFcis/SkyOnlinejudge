<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

class judge
{
    private $socket;
    private $challenge;
    
    function __construct($socket, $challenge)
    {
        $this->$socket=$socket;
        $this->$challenge=$challenge;
    }
    
    public function start()
    {
        
    }
}

?>