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

            return false;
        }

        if (!socket_write($this->socket, $txt, strlen($txt))) {

            return false;
        }

        while ($result = socket_read($this->socket, 2048)) {

            return $result;
        }
    }
}

class post
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function send($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $temp=curl_exec($ch);
        curl_close($ch);

        return $temp;
    }
}
