<?php namespace SkyOJ\File;

class ManagerBase
{
    protected $subrootname = '';
    function base():string
    {
        return Path::base().$this->subrootname;
    }

    function mkdir($path):bool
    {
        $full = $this->base().$path;
        if( is_dir($full) ) return true;
        return mkdir($full,0777,true);
    }

    function read(string $path,bool $blank = true)
    {
        $full = $this->base().$path;
        if( !file_exists($full) )
        {
            if( $blank ) return '';
            throw new ManagerBaseException('No Such File!');
        }
        $data = file_get_contents($full);
        if( $data === false )
        throw new ManagerBaseException('Get File Error!');
        return $data;
    }

    function write(string $path,string $data)
    {
        $full = $this->base().$path;
        return file_put_contents($full,$data) !== false;
    }

    function move($source,$target)
    {
        $source = $this->base().$source;
        $target = $this->base().$target;
        if( !rename($source,$target) )
            throw new ManagerBaseException('Move File Error!');
    }

    function copy($source,$target)
    {
        $source = $this->base().$source;
        $target = $this->base().$target;
        if( !copy($source,$target) )
            throw new ManagerBaseException('Copy File Error!');
    }
}

class ManagerBaseException extends \Exception { }
