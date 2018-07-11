SKY Online Judge (Developing)
=================
>An open source online judge web system 

Required
-------------
1. PHP 7.2.0 +<br>
   Required modules:
   1. pdo
   2. pdo-mysql
   3. gmp
2. MySQL or MariaDB

Install
-------------
1. Clone SKY Online Judge Repository to yout website (Don't forget to fetch submodules)
2. Copy `config/config.example.php` to `config/config.php`
3. Fill out your MySQL setting in config.php
4. Copy `LocalSetting.example.php` to `LocalSetting.php` then set its starting directory at `$_E['SITEDIR']`
5. Install [composer](https://getcomposer.org/) or put composer.phar to site root
6. use `php composer.phar install` or `composer install` to install php library
7. use `./vendor/bin/phinx m` to set up your database.
8. start your SKY Online Judge ~
