SHS-Config
==========

Updates to the previous Config6 Framework.

Major updates include:

* Router based requests using regular expression matching
* Views have separation of logic and layout
* Integration of Twig template engine
* Support for Composer
* Support for Backbone, Handlebars, and Grunt

## Installation Instructions Coming Soon

1. Clone repo into local folder

    `git clone git@github.com:shadyhill/SHS-Config.git *new_project*`

2. Setup new virtual host
    ```
    cd /etc/apache2/extra/
    sudo vi httpd-vhosts.conf
    ```

   Add the following to the file
    ```html
    <VirtualHost *:80>
        ServerName *new_project*
        DocumentRoot /path/to/project/
    </VirtualHost>
    ```
3. Restart Apache

    `sudo apachectl graceful`

4. Add project name to your hosts file

    `sudo vi /etc/hosts`

   And add a line for your project to the end of the file

    `127.0.0.1   sitename`

5. Rename sample.htaccess to .htaccess

6. Create a new database and import sample-db.sql

7. Rename config.sample.php to config.php and update mysql connection values 

8. Install composer dependencies

    `composer install`

9. Install js dependencies

    ```
    cd assets/
    npm install
    ```


