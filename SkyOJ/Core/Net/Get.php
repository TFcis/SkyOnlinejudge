<?php namespace SkyOJ\Core\Net;

class Get
{
    static $last_error;
    static public function send(string $url,array $data = [],?string $cookie = null)
    {
        ksort( $data );
        $data = http_build_query( $data );
        $ch = curl_init();

        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_ENCODING, "UTF-8" );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );
        curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , true );
        curl_setopt( $ch , CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36' );
        if( isset($cookie) )
        {
            curl_setopt( $ch , CURLOPT_COOKIEFILE, $cookie );
            curl_setopt( $ch , CURLOPT_COOKIEJAR , $cookie );
        }

        $res = curl_exec($ch);
        self::$last_error = null;
        if( curl_errno($ch) )
        {
            self::$last_error = curl_errno($ch);
        }
        curl_close($ch);
        return $res;
    }
}