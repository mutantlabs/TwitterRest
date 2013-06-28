<?php
/**
 * Created by Mutant Labs
 * User: mijahn
 * Date: 27/06/13
 * Time: 12:03
 */

namespace TwitterRest;
require_once('config.php');
require_once('vendor/autoload.php');
require "TwitterRest/TwitterRestAPI.php";
use TwitterOAuth;
use TwitterRest\TwitterRestAPI;

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
    ->addGetRoute('authenticate', function(){
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $temporary_credentials = $connection->getRequestToken(OAUTH_CALLBACK);
        $redirect_url = $connection->getAuthorizeURL($temporary_credentials, FALSE);

        return array($temporary_credentials,$redirect_url);
    })
    ->addGetRoute('success', function(){
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_GET['oauth_token'],
            $_GET['oauth_verifier']);
        $token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        return array($token_credentials);
    })
    ->run();
