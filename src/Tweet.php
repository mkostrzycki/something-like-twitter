<?php

class Tweet
{

    const NON_EXISTING_ID = -1;

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct()
    {
        $this->id = self::NON_EXISTING_ID;
    }

    /////////////////////////////////

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    //////////////////////////////////

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }
    
    //////////////////////////////////////
    
    static public function loadTweetById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Tweets WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['user_id'];
            $loadedTweet->text = $row['tweet_text'];
            $loadedTweet->creationDate = $row['creation_date'];

            return $loadedTweet;
        }

        return null;
    }
    
    public function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $stmt = $conn->prepare("SELECT Tweets.id, Tweets.user_id, Tweets.tweet_text, Tweets.creation_date FROM Tweets JOIN Users ON Tweets.user_id=Users.id WHERE Users.id=:id ORDER BY Tweets.id DESC");

        // @ToDo - przerobić na wyciąganie samych id Tweetów i odpalanie w pętli foreach loadTweetById()
        
        $ret = [];

        $result = $stmt->execute(['id' => $userId]);

        if ($result !== false && $result->rowCount() != 0) {

            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['tweet_text'];
                $loadedTweet->creationDate = $row['creation_date'];
                
                $ret[] = $loadedTweet;
            }
        }

        return $ret;
    }
    
    static public function loadAllTweets(PDO $conn)
    {
        $sql = "SELECT * FROM Tweets ORDER BY id DESC";

        $ret = [];

        $result = $conn->query($sql);

        if ($result !== false && $result->rowCount() != 0) {

            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['user_id'];
                $loadedTweet->text = $row['tweet_text'];
                $loadedTweet->creationDate = $row['creation_date'];
                
                $ret[] = $loadedTweet;
            }
        }

        return $ret;
    }
    
    public function saveToDB(PDO $conn)
    {
        if ($this->id == self::NON_EXISTING_ID) {
            //Saving new tweet to DB
            $stmt = $conn->prepare(
                    'INSERT INTO Tweets(user_id, tweet_text, creation_date) VALUES (:userId, :text, :creationDate)'
            );

            $result = $stmt->execute(
                    ['userId' => $this->userId, 'text' => $this->text, 'creationDate' => $this->creationDate]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();

                return true;
            } else {
                echo ':(';
            }
        } else {
            //Updating user in DB
            $stmt = $conn->prepare(
                    'UPDATE Tweets SET user_id=:userId, tweet_text=:text, creation_date=:creationDate WHERE id=:id'
            );

            $result = $stmt->execute(
                    ['userId' => $this->userId, 'text' => $this->text,
                        'creationDate' => $this->creationDate, 'id' => $this->id]
            );

            if ($result === true) {

                return true;
            }
        }

        return false;
    }

}
