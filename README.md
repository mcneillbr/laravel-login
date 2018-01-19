sistema de login



chown -Rv www-data:www-data /var/www
find /var/www -type f -exec chmod 640 {} \;
find /var/www -type d -exec chmod 750 {} \;

cd /var/www
HOME_PRJ=laravel-login
find /var/www/$HOME_PRJ -type f -exec chmod 640 {} \;
find /var/www/$HOME_PRJ -type d -exec chmod 750 {} \;


chmod 760 /var/www/$HOME_PRJ/bootstrap
find /var/www/$HOME_PRJ/bootstrap -type f -exec chmod 640 {} \;
find /var/www/$HOME_PRJ/bootstrap -type d -exec chmod 760 {} \;

chmod 760 /var/www/$HOME_PRJ/storage
find /var/www/$HOME_PRJ/storage -type f -exec chmod 640 {} \;
find /var/www/$HOME_PRJ/storage -type d -exec chmod 760 {} \;
find /var/www/$HOME_PRJ/storage/images -type d -exec chmod 760 {} \;

1.0 create Database and user

CREATE DATABASE `sys-login` /*!40100 COLLATE 'utf8_bin' */;

CREATE USER 'http-user'@'localhost';
GRANT SELECT  ON *.* TO 'http-user'@'localhost';
GRANT SELECT, EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES  ON `sys-login`.* TO 'http-user'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

SHOW GRANTS FOR 'http-user'@'localhost';

2.0v migrations

php artisan make:migration create_users_table --create=users

php artisan migrate

3.0

php artisan make:seeder UsersTableSeeder


php artisan db:seed --class=UsersTableSeeder
