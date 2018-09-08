<?php namespace SkyOJ\Scoreboard;

class OJCaptureProfileEnum extends \SkyOJ\Helper\SqlEnum
{
    protected static $table = 'ojcapture_profile';
    const None = 0;

    public static function create(string $profile_name, int $ojcapture, string $profile = '')
    {
        $data = [
            'text'      => $profile_name,
            'ojcapture'     => $ojcapture,
            'profile'   => $profile
        ];
        self::insertinto($data);
    }
}