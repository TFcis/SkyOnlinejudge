<?php namespace SkyOJ\Helper;

class DirScanner
{
    static function open(string $base):array
    {
        return glob($base);
    }
}