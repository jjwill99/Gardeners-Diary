# Gardeners-Diary
Tool which aims to help a busy and forgetful person manage the tasks associated with their small garden.

# How to install and run
The application was ran locally using WAMP server 3.2.3.

1. Download and install WAMP server 3.2.3
2. Make sure the server is using MySQL 5.7.31 and PHP 7.3.21
3. Create folder called "Laravel" in "\wamp64\www"
4. Create folder called "Gardeners-Diary" in "\wamp64\www\Laravel"
5. Put the GitHub contents into the "Laravel" folder
6. Update the "DocumentRoot" and the "Directory" tag underneath it to "${INSTALL_DIR}/www/Laravel/Gardeners-Diary/public"
    Located in "\wamp64\bin\apache\apache2.4.46\conf\httpd.conf" and "\wamp64\bin\apache\apache2.4.46\conf\extra\httpd-vhosts.conf"
7. Using a terminal window at "\wamp64\www\Laravel\Gardeners-Diary", run command "php artisan migrate" to set up the database
8. Application should now be set up, use a browser to access it at "localhost"