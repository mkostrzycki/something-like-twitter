<?php

/**
 * Klasa do obsługi komentarzy
 */
class Comment
{
    /**
     * Stała określająca ID obiektu nieistniejącego w bazie.
     */
    const NON_EXISTING_ID = -1;
    
    /**
     *
     * @var int ID komentarza
     */
    private $id;
    
    /**
     *
     * @var int ID użytkownika, który stworzył komentarz
     */
    private $userId;
    
    /**
     *
     * @var int ID tweeta, do którego należy komentarz
     */
    private $tweetId;
    
    /**
     *
     * @var string Treść komentarza
     */
    private $text;
    
    /**
     *
     * @var date Data stworzenia komentarza
     */
    private $creationDate;
    
    /////////////////////////////////
    
    /**
     * Konstruktor ustawia ID na -1 a pozostałe atrybuty na puste stringi.
     */
    public function __construct()
    {
        $this->id = self::NON_EXISTING_ID;
        $this->userId = self::NON_EXISTING_ID;
        $this->tweetId = self::NON_EXISTING_ID;
        $this->text = '';
        $this->creationDate = '';
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

    public function getTweetId()
    {
        return $this->tweetId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /////////////////////////////////
    
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
        return $this;
    }

    public function setTweetId($tweetId)
    {
        $this->tweetId = (int) $tweetId;
        return $this;
    }

    public function setText($text)
    {
        $this->text = (string) $text;
        return $this;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = (string) $creationDate;
        return $this;
    }

    /////////////////////////////////
    
    static public function loadCommentById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Comments WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['user_id'];
            $loadedComment->tweetId = $row['tweet_id'];
            $loadedComment->text = $row['comment_text'];
            $loadedComment->creationDate = $row['creation_date'];

            return $loadedComment;
        }

        return null;
    }
    
    static public function loadAllCommentsByTweetId(PDO $conn, $tweetId)
    {
        $stmt = $conn->prepare("SELECT Comments.id, Comments.user_id, Comments.tweet_id, Comments.comment_text, Comments.creation_date FROM Comments JOIN Tweets ON Comments.tweet_id=Tweets.id WHERE Tweets.id=:id ORDER BY Comments.id DESC");
 
        // @ToDo - przerobić na wyciąganie samych id Commentsów i odpalanie w pętli foreach loadCommentById()
        
        $ret = [];

        $result = $stmt->execute(['id' => $tweetId]); 

        if ($result !== false && $stmt->rowCount() != 0) {

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['user_id'];
                $loadedComment->tweetId = $row['tweet_id'];
                $loadedComment->text = $row['comment_text'];
                $loadedComment->creationDate = $row['creation_date'];
                
                $ret[] = $loadedComment;
            }
        }

        return $ret;
    }
    
    public function saveToDB(PDO $conn)
    {
        if ($this->id == self::NON_EXISTING_ID) {
            //Saving new comment to DB
            $stmt = $conn->prepare(
                    'INSERT INTO Comments(user_id, tweet_id, comment_text, creation_date) VALUES (:userId, :tweetId, :text, :creationDate)'
            );

            $result = $stmt->execute(
                    [
                        'userId' => $this->userId, 
                        'tweetId' => $this->tweetId, 
                        'text' => $this->text, 
                        'creationDate' => $this->creationDate
                    ]
            );

            if ($result === true) {
                $this->id = $conn->lastInsertId();

                return true;
            }
            
        } else {
            //Updating comment in DB
            $stmt = $conn->prepare(
                    'UPDATE Comments SET user_id=:userId, tweet_id=:tweetId, comment_text=:text, creation_date=:creationDate WHERE id=:id'
            );

            $result = $stmt->execute(
                    [
                        'userId' => $this->userId, 
                        'tweetId' => $this->tweetId, 
                        'text' => $this->text,
                        'creationDate' => $this->creationDate, 
                        'id' => $this->id
                    ]
            );

            if ($result === true) {

                return true;
            }
        }

        return false;
    }

}
