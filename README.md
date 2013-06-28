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

create config.php as follows:

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

Extended Auth flow taken from https://github.com/abraham/twitteroauth

```php
    ->addGetRoute('authenticate', function(){
            //When a user lands on /authenticate we build a new TwitterOAuth object using the client credentials.
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

            //Using the built $connection object you will ask Twitter for temporary credentials. The oauth_callback value is required.
            $temporary_credentials = $connection->getRequestToken(OAUTH_CALLBACK);

            //Once we have temporary credentials the user has to go to Twitter and authorize the app to access and updates their data.
            $redirect_url = $connection->getAuthorizeURL($temporary_credentials, FALSE);

            return array($temporary_credentials,$redirect_url);
        })
```

The user is now on twitter.com and may have to login. Once authenticated with Twitter they will will either have to click on allow/deny, or will be automatically redirected back to the callback in this case its example.domain.com/success
Once the user has returned to /success and allowed access we need to build a new TwitterOAuth object using the temporary credentials.

```php
        ->addGetRoute('success', function(){
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_GET['oauth_token'],
                $_GET['oauth_verifier']);

            //Now we ask Twitter for long lasting token credentials. These are specific to the application and user and will act like password to make future requests.
            $token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);

            Here we can from our token credentials back to the application (i'd suggest a more secure method of sending these than encoded JSON. but here for example):
            return array($token_credentials);
        })

```

From here with the token credentials we build a new TwitterOAuth object.

```php
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token_credentials['oauth_token'],
$token_credentials['oauth_token_secret']);
```

And make requests authenticated as the user:

```php
$status = $connection->post('statuses/update', array('status' => 'Text of status here', 'in_reply_to_status_id' => 123456));
```

License
-------

 - Licensed under the MIT License. See the LICENSE file for more details.
 - marcj/php-rest-service is Licensed under the MIT License. See https://github.com/marcj/php-rest-service/blob/master/LICENSE for more details
 - abraham/twitteroauth https://github.com/abraham/twitteroauth/blob/master/LICENSE
