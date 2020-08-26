<?php
class ddbb {
    private $conn;

    /**
     * Set connection to database
     */
    public function __construct()
    {
        $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/config.ini');

        $dbhost = $config['dbhost'];
        $dbuser = $config['dbuser'];
        $dbpass = $config['dbpass'];
        $db     = $config['db'];

        $dsn = "mysql:host=$dbhost;dbname=$db";

        $this->conn = new PDO($dsn, $dbuser, $dbpass);
    }

    /**
     * Insert tweet into the database
     *
     * @param Array $data
     * @return void
     */
    function insertTimeline ($data = null) {
        if($data == null) {
            return "Error, data not found.";
        }

        $conn = $this->conn;
        $mysqldate = date( 'Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO `tweets`(`user_id`, `tweet_id`, `message`, `updated_at`,`created_at`)
                        VALUES (:user_id, :tweet_id, :message, :updated_at, :created_at)
                        ON DUPLICATE KEY UPDATE tweet_id=tweet_id, updated_at = :updated_at");

        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':tweet_id', $data['tweet_id'], PDO::PARAM_STR);
        $stmt->bindParam(':message', $data['message'], PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $mysqldate, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $mysqldate, PDO::PARAM_STR);
        $res = $stmt->execute();

        if(!$res) {
            var_dump($data,$res, $stmt);die();
        }

        addLog($data['log_msg']);
    }

    /**
     * Get and returns the tweets that have not been answered
     *
     * @param Integer $user_id
     * @return Array
     */
    function getTweetsNotReplied($user_id) {
        $conn = $this->conn;

        $stmt = $conn->prepare("SELECT * 
                                FROM `tweets` 
                                WHERE replied = 0 
                                AND user_id = :user_id");

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $res = $stmt->execute();

        if(!$res) {
            var_dump('ERROR');die();
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    /**
     * Update the tweet to replied = true
     *
     * @param Integer $tweet_id
     * @return void
     */
    function setReplied($tweet_id) {
        $conn = $this->conn;
        $mysqldate = date( 'Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE `tweets`
                                SET `replied` = 1, updated_at = :updated_at
                                WHERE tweet_id = :tweet_id");

        $stmt->bindParam(':tweet_id', $tweet_id, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $mysqldate, PDO::PARAM_STR);
        $res = $stmt->execute();

        if(!$res) {
            var_dump('ERROR RES setReplied()');die();
        }
    }
}
