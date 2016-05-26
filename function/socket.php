<?php

if (!defined('IN_SKYOJSYSTEM')) {
    exit('Access denied');
}

class socket
{
    private $host;
    private $bind;
    private $port;
    private $socket;
    //private $connection;

    public function __construct($host, $port, $bind)
    {
        $this->host = $host;
        $this->bind = $bind;
        $this->port = $port;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function send($txt)
    {
        //socket_bind($this->socket,$this->bind);
        $connection = socket_connect($this->socket, $this->host, $this->port);
        if ($connection === false) {
            LOG::msg(Level::Warning, 'can not connect to judgesystem');

            return false;
        }

        if (!socket_write($this->socket, $txt, strlen($txt))) {
            LOG::msg(Level::Warning, 'can not send data to judgesystem');

            return false;
        }
        LOG::msg(Level::Debug, 'wait judge!!!');
        while ($result = socket_read($this->socket, 2048)) {
            echo 'debug';

            return $result;
        }
    }

}