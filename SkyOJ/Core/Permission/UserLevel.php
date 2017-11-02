<?php namespace SkyOJ\Core\Permission;

class UserLevel extends \SkyOJ\Helper\Enum
{
    const GUEST  = 0;
    const BANNED = 2;
    const UNVERIFIED = 4;
    const USER = 6;
    const ADMIN = 8;
    const ROOT = 100;
}