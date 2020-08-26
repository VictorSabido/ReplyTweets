<?php
use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter {
    private $conn;

    /**
     * Set connection to database
     */
    public function __construct()
    {
        $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/config.ini');

        $this->conn = new TwitterOAuth(
            $config['CONSUMER_KEY'], 
            $config['CONSUMER_SECRET'], 
            $config['ACCESS_TOKEN'], 
            $config['ACCESS_TOKEN_SECRET']
        );
    }

    /**
     * Get Twitter Account data by @xxxx
     *
     * @param String $user
     * @return Object
     */
    public function getTwitterAccount($user) {
        $content = $this->conn->get("users/show", [
            'screen_name' => $user
        ]);
        
        return $content;
    }
    
    /**
     * Get Twitter Timeline by UserID
     *
     * @param Integer $id
     * @return Object
     */
    public function getUserTimeline($id) {
        $content = $this->conn->get("statuses/user_timeline", [
            'user_id' => $id,
            'exclude_replies' => 'true',
            'include_rts' => false
        ]);

        foreach($content as $tweet) {
            $msg = $tweet->id .'-'.$tweet->user->screen_name.' ';
            $msg .= '{'.$tweet->text.'}';
            
            $msg = str_replace(array("\r","\n"),"",$msg);
            
            $data = [
                'user_id' => $id,
                'tweet_id' => $tweet->id_str,
                'message' => $tweet->text,
                'log_msg' => $msg
            ];

            $ddbb = new ddbb;
            $ddbb->insertTimeline($data);
        }

        return $content;
    }

    /**
     * Write a tweet and return the information
     *
     * @param String $msg
     * @return Object
     */
    public function writeTweet($msg) {
        $content = $this->conn->post('statuses/update', [
            'status' => $msg
        ]);

        return $content;
    }
    
    /**
     * Reply to the requested tweet
     *
     * @param Integer $tweet_id
     * @param String $msg
     * @return void
     */
    public function replyTweet($tweet_id, $msg) {
        $this->conn->post('statuses/update', [
            'in_reply_to_status_id' => $tweet_id,
            'status' => $msg
        ]);
    }
}