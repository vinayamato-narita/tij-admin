# go to project
# install app's dependencies
$ composer install

# install app's dependencies
$ install node v14.16.0
$ install npm v6.14.11

$ cp env.example .env

# edit connect database
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=transeeker
DB_USERNAME=root
DB_PASSWORD=root

# Setup debugging
### Install Visual Studio Code                      
    https://code.visualstudio.com/download
### Install Php Debug plugin for Visual Studio Code 
    https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug
### Download XDebug module for PHP, choose correct version for current installed PHP version
    https://xdebug.org/download
### Copy downloaded xdebug dll to ext folder of PHP
For example: c:/xampp/php/ext
### Add following line to the end of php.ini file (c:/xampp/php/php.ini)
    [XDebug]
    zend_extension=<downloaded dll name>.dll
    xdebug.mode = debug
    xdebug.start_with_request = yes
    xdebug.client_port = 9000
### Start debugging
In Visual Studio Code, press Ctirl + Shift + D to open Debug Window

Click "Create a launch.json file" to create debug configuration, modify port value to 9000

Finally, create some breakpoints and press F5 for starting debug
### Next step

``` bash
# in your app directory
# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate:refresh --seed

# generate mixing
$ npm run dev or npm run build

# and repeat generate mixing
$ npm run dev

# login with account admin@vinayamato.com/12345678
