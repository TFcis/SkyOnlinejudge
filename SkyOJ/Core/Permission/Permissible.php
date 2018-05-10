<?php namespace SkyOJ\Core\Permission;

use SkyOJ\Core\User\User;

interface Permissible
{
    public function readable(User $user):bool;
    public function writeable(User $user):bool;
    public static function creatable(User $user):bool;
}