SKY Online Judge (Developing)
=================
>An open source online judge web system 

Required
-------------
PHP 7.0.0 +
MySQL

Install
-------------
###On Ubuntu Linux
1. Clone SKY Online Judge Repository to yout website
2. Make sure you have installed PHP and MySQL
3. Copy `config/config.example.php` to `config/config.php`
4. Fill out your MySQL setting in `config.php`
5. Run `install/setMySQL.php` in command line to set table. If everything is fine, it will show `SUCC`
<br>`cd install`
<br>`php setMySQL.php`
6. Copy `LocalSetting.example.php` to `LocalSetting.php`, then set its starting directory at `$_E['SITEDIR']`
7. start your SKY Online Judge ~
