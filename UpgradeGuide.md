# Hot to update to API_INTERFACE Version

1. use git pull to `TFcis/PHP7`
2. run `composer update`
3. config new version `config/config.php`
4. run `/vendor/bin/phinx`
5. run `php tools/20171102_newproblem_update_helper.php`