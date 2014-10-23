SKY Online Judge (Developing)
=================
>An open source online judge web system 

Required
-------------
PHP 5.5.0 +

Install
-------------
###On Koding
1. Clone SKY Online Judge Repository to yout website
2. Build your MySQL with `sudo apt-get install mysql-server mysql-common mysql-client php5-mysql`
3. Restart your apache2 with  `sudo service apache2 restart`
4. Open folder `config` and you will find a file named `config.example.php`
5. Fill out your MySQL setting and rename file to `config.php`
6. Run `install/setMySQL.php` in command line to set table. If everything is fine, it will show `SUCC`
7. start your SKY Online Judge ~

###PCNTL ON KODING
1. `mkdir tmp`
2. `cd tmp`
3. `apt-get source php5`
4. `cd php*/ext/pcntl`
5. `phpize`
6. `./configure`
7. `make`
8. `sudo cp modules/pcntl.so /usr/lib/php5/20121212/`
9. `sudo echo "extension=pcntl.so" > /etc/php5/apache2/conf.d/pcntl.ini`
10. `sudo vim /etc/php5/apache2/php.ini`
11. comment disable_functions = pcntl... (add ; ahead)
12. `sudo service apache2 restart`

