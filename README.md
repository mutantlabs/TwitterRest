twitter-rest
============

A simple Class to interface with Twitter API v1.1 using OAuth, and return tweets as JSON via a get method. Uses RestService and twitteroauth

Config

rename config.sample.php to config.php

Set your config.php (from twitteroauth https://github.com/abraham/twitteroauth)

```php
define('CONSUMER_KEY', 'CONSUMER_KEY_HERE');
define('CONSUMER_SECRET', 'CONSUMER_SECRET_HERE');
define('OAUTH_TOKEN', 'OAUTH_TOKEN_HERE');
define('OAUTH_TOKEN_SECRET', 'OAUTH_TOKEN_SECRET_HERE');
define('OAUTH_CALLBACK', 'http://example.com/twitteroauth/callback.php');
```

Requires https://github.com/marcj/php-rest-service

Install php-rest-service with Composer:

 - https://packagist.org/packages/marcj/php-rest-service.
 - More information available under https://packagist.org/.

Create a `composer.json`:

```json
{
    "require": {
        "marcj/php-rest-service": "*"
    }
}
```

and run

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

#TwitterRestAPIr Class usage

Include TwitterRestAPI

```php
require 'TwitterRestAPI.php';
```

Construct new TwitterRestAPI

```php
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

Requirements
------------

 - PHP 5.3 and above.
 - PHPUnit to execute the test suite.
 - Setup PATH_INFO in mod_rewrite (.htaccess) or other webserver configuration

Example:
```
//apache .htaccess
RewriteEngine On
RewriteRule (.+) index.php/$1 [L]
```

API Method using php-rest-service
----------

```php
namespace twitterRestAPI;
require "TwitterRestAPI.php";
use twitterRestAPI\TwitterRestAPI;

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
