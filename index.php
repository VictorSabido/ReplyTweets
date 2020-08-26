<?php
require "vendor/autoload.php";
include "src/Twitter.php";
include "src/ddbb.php";

use \Statickidz\GoogleTranslate;

date_default_timezone_set('Europe/Madrid');

/**
 * Translate a text string and return it
 *
 * @param String $text
 * @return String
 */
function translate($text) {
    $source = 'es';
    $target = 'pt';
    
    $trans = new GoogleTranslate();
    $result = $trans->translate($source, $target, $text);
    
    return $result;
}

/**
 * Add a text string with date and time to the log
 *
 * @param String $msg
 * @return String
 */
function addLog($msg) {
    $file = 'logs/'.date('Y-m-d').'.txt';
    $txt = '['.date('Y/m/d H:i:s').'] ';
    $txt .= $msg;

    file_put_contents($file, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
}

try {
    $account= ''; // <-- Fill with twitter account. For example: Cristiano
    $twitter = new Twitter;
    $tweetID = $twitter->getTwitterAccount($account);
    
    $screen_name = '@'.$tweetID->screen_name;
    $tweetID = $tweetID->id;
    $twitter->getUserTimeline($tweetID);
    $ddbb = new ddbb;
    $tweets = $ddbb->getTweetsNotReplied($tweetID);
    foreach($tweets as $tweet) {
        $msg = translate($tweet['message']);
        $twitter->replyTweet($tweet['tweet_id'], $screen_name.' '.$msg);
        $ddbb->setReplied($tweet['tweet_id']);

        addLog($tweet['tweet_id'].' ('.$screen_name.') Replied with-> '.$msg);
    }

    echo "Success!";
    addLog('Success');
} catch (\Throwable $th) {
    addLog('Error');
    var_dump($th);die();
}
