<?php namespace SkyOJ\Helper\Env;

class UploadLimits 
{
    //return bytes
    // https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
    public static function value()
    {
        static $size = null;

        if( isset($size) )
            return $size;
        
        $post_max_size = self::parse_size(ini_get('post_max_size'));
        $upload_max_filesize = self::parse_size(ini_get('upload_max_filesize'));
        $size = min($post_max_size, $upload_max_filesize);

        return $size;
    }

    private static function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if($unit)
        {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else
        {
            return round($size);
        }
    }
}