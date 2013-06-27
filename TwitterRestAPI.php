<?php
/**
 * Created by:

      _   .-')                .-') _      ('-.         .-') _  .-') _                      ('-.    .-. .-')    .-')
    ( '.( OO )_             (  OO) )    ( OO ).-.    ( OO ) )(  OO) )                    ( OO ).-.\  ( OO )  ( OO ).
    ,--.   ,--.),--. ,--.  /     '._   / . --. /,--./ ,--,' /     '._        ,--.       / . --. / ;-----.\ (_)---\_)
    |   `.'   | |  | |  |  |'--...__)  | \-.  \ |   \ |  |\ |'--...__)       |  |.-')   | \-.  \  | .-.  | /    _ |
    |         | |  | | .-')'--.  .--'.-'-'  |  ||    \|  | )'--.  .--'       |  | OO ).-'-'  |  | | '-' /_)\  :` `.
    |  |'.'|  | |  |_|( OO )  |  |    \| |_.'  ||  .     |/    |  |          |  |`-' | \| |_.'  | | .-. `.  '..`''.)
    |  |   |  | |  | | `-' /  |  |     |  .-.  ||  |\    |     |  |         (|  '---.'  |  .-.  | | |  \  |.-._)   \
    |  |   |  |('  '-'(_.-'   |  |     |  | |  ||  | \   |     |  |          |      |   |  | |  | | '--'  /\       /
    `--'   `--'  `-----'      `--'     `--' `--'`--'  `--'     `--'          `------'   `--' `--' `------'  `-----'

 * Coder: Mijahn - with help from Rich
 * Date: 27/06/13
 * Time: 11:53
 *
 */

namespace twitterRestAPI;
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
require_once('vendor/autoload.php');
use RestService\Server;
use TwitterOAuth;

class TwitterRestAPI extends Server {

    private $twitter;

    public function __construct($url = '/') {
        parent::__construct($url); // because we need to
        $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
    }

    //@override from RestService\Server
    public function send($pData)
    {
        return $this->getClient()->sendResponse(200, array('data' => $pData)); // override so you can change 'data' to whatever you like on the success method
    }

    public function getUserStatus() { // access this directly for up to the second tweets - will use a twitter API request.
        return $this->twitter->get('statuses/user_timeline');
    }

    public function getCachedUserStatus() { // to save your twitter api limit getting hammered
        if (file_exists('twitter_result.data')) {
            $data = unserialize(file_get_contents('twitter_result.data'));
            if ($data['timestamp'] > time() - 10 * 60) {
                $twitter_result = $data['twitter_result'];
            }
        }

        if (!isset($twitter_result)) { // cache doesn't exist or is older than 10 mins
            $twitter_result = $this->getUserStatus();
            $data = array ('twitter_result' => $twitter_result, 'timestamp' => time());
            file_put_contents('twitter_result.data', serialize($data));
        }
        return $twitter_result;

    }

}