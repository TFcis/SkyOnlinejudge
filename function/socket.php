<?php
if( !defined('IN_SKYOJSYSTEM') )
{
    exit('Access denied');
}

class socket
{
    private $host;
    private $port;
    private $socket;
    //private $connection;
    
    function __construct($host, $port)
    {
        $this->$host=$host;
        $this->$port=$port;
        $this->$socket=socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        
    }
    
    public function send($txt)
    {
        $connection=socket_connect($this->$socket,$this->$host,$this->$port);
        if($connection === false){
            LOG::msg(Level::Warning,"can not connect to judgesystem");
            return false;
        }
        
        if(!socket_write($socket, $txt, strlen($txt)))
        {
            LOG::msg(Level::Warning,"can not send data to judgesystem");
            return false;
        }
        
        while($result = socket_read($socket, 2048)) 
        {
            return $result;
        }
    }
    
}

?>