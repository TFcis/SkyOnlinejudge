<?php namespace SkyOJ\Judge;

class JudgeProfileEnum extends \SkyOJ\Helper\SqlEnum
{
    protected static $table = 'judge_profile';
    const None = 0;

    public static function create(string $profile_name, int $judge, string $profile = '')
    {
        $data = [
            'text'      => $profile_name,
            'judge'     => $judge,
            'profile'   => $profile
        ];
        self::insertinto($data);
    }
}