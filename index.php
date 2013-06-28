<?php
/**
 * Created by Mutant Labs
 * User: mijahn
 * Date: 27/06/13
 * Time: 12:03
 */

namespace TwitterRest;
require "TwitterRestAPI.php";
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
    ->run();
