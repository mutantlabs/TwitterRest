twitter-rest
============

A simple Class to interface with Twitter API v1.1 using OAuth, and return tweets as JSON via a get method. Uses RestService and twitteroauth

#Install

Requires https://github.com/marcj/php-rest-service

Install twitter-rest with Composer:

 - https://packagist.org/packages/mutantlabs/twitter-rest
 - More information available under https://packagist.org/.

Create a `composer.json`:

```json
{
    "require": {
        "mutantlabs/twitter-rest": "dev-master"
    }
}
```

and run

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```
Requirements
------------

 - PHP 5.3 and above.
 - PHPUnit to execute the test suite.
 - Setup PATH_INFO in mod_rewrite (.htaccess) or other webserver configuration

Example:
```
//apache .htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

#Config

rename config.sample.php to config.php

Set your config.php (uses twitteroauth from https://github.com/abraham/twitteroauth)

```php
define('CONSUMER_KEY', 'CONSUMER_KEY_HERE');
define('CONSUMER_SECRET', 'CONSUMER_SECRET_HERE');
define('OAUTH_TOKEN', 'OAUTH_TOKEN_HERE');
define('OAUTH_TOKEN_SECRET', 'OAUTH_TOKEN_SECRET_HERE');
define('OAUTH_CALLBACK', 'http://example.com/twitteroauth/callback.php');
```

#Example use of TwitterRestAPI

include the vendor/autoload.php to make the class in your script available.

```php
include 'vendor/autoload.php';
```

##Basic use:

Construct new TwitterRestAPI

```php
use TwitterRest\TwitterRestAPI;
require_once('config.php');
$twitterRestApi = new TwitterRestAPI();
```

Get some tweets
```php
$tweets = $twitterRestApi->getCachedUserStatus();
$arrTweets = array();
foreach($tweets as $key => $status) {
    $arrTweets[$key] = array(
        'created_at' => $status->created_at,
        'text' => $status->text
    );
}
echo json_encode($arrTweets);
```

Note - to use getCachedUserStatus() - you need to make your apache server the owner of twitter_result.data:

```bash
sudo chown -R www-data:www-data twitter_result.data
```
#Create a REST API Method using php-rest-service
----------

```php

use TwitterRest\TwitterRestAPI;
require_once('config.php');

TwitterRestAPI::create('/')
    ->addGetRoute('', function(){
        $twitterRestApi = new TwitterRestAPI();
        $tweets = $twitterRestApi->getCachedUserStatus();
        $arrTweets = array();
        foreach($tweets as $key => $status) {
            $arrTweets[$key] = array(
                'created_at' => $status->created_at,
                'text' => $status->text
            );
        }
        return $arrTweets;
    })
    ->run();
```

License
-------

 - Licensed under the MIT License. See the LICENSE file for more details.
 - marcj/php-rest-service is Licensed under the MIT License. See https://github.com/marcj/php-rest-service/blob/master/LICENSE for more details
