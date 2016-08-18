SKY Online Judge (Developing)
=================
>An open source online judge web system 

Required
-------------
1. PHP 7.0.0 +<br>
   Required modules:
   1. pdo
   2. pdo-mysql
   3. gmp
   4. mcrypt
2. MySQL or MariaDB

Install
-------------
1. Clone SKY Online Judge Repository to yout website
2. Copy `config/config.example.php` to `config/config.php`
3. Fill out your MySQL setting in config.php
4. Set up your database using `install/FullSQLFormat.sql`.
5. Copy `LocalSetting.example.php` to `LocalSetting.php` then set its starting directory at `$_E['SITEDIR']`
6. start your SKY Online Judge ~
