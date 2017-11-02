<?php namespace SkyOJ\Core\Permission;

class ObjectLevel extends \SkyOJ\Helper\Enum
{
    const EVERYONE = -1;
    const LOGIN = 1;
    const USER = 5;
    const USER_PRIVATE = 6;
    const ADMIN = 7;
    const ADMIN_PRIVATE = 8;
    const ROOT = 99;
}