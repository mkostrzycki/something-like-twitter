<?php

class Tweet
{

    /**
     * Stała określająca ID obiektu nieistniejącego w bazie.
     */
    const NON_EXISTING_ID = -1;

    /**
     *
     * @var int ID tweeta
     */
    private $id;
    
    /**
     *
     * @var int ID użytkownika, który stworzył tweeta
     */
    private $userId;
    
    /**
     *
     * @var string Treść tweeta
     */
    private $text;
    
    /**
     *
     * @var date Data stworzenia tweeta
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
        $this->text = '';
        $this->creationDate = '';
    }

    /////////////////////////////////

    /**
     * Zwraca ID tweeta
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Zwraca ID użytkownika, który stworzył tweeta
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Zwraca treść tweeta
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Zwraca datę stworzenia tweeta
     * @return date
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    //////////////////////////////////

    /**
     * Ustawia ID użytkownika, który stworzył tweeta
     * @param int $userId ID użytkownika, który stworzył tweeta
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Ustawia treść tweeta
     * @param string $text Treść tweeta
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Ustawia datę stworzenia tweeta
     * @param date $creationDate Data stworzenia tweeta
     * @return $this
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }
    
    //////////////////////////////////////
    
    /**
     * Pobiera z bazy danych tweet o podanym ID. Zwraca obiekt klasy Tweet lub null, jeżeli w bazie nie ma podanego ID.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $id ID tweeta
     * @return \Tweet|null
     */
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
    
    /**
     * Zwraca tablicę obiektów klasy Tweet - wszystkie tweety stworzone przez użytkownika o wskazanym ID.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $userId ID użytkownika, który stworzył tweeta
     * @return \Tweet
     */
    static public function loadAllTweetsByUserId(PDO $conn, $userId)
    {
        $stmt = $conn->prepare("SELECT Tweets.id, Tweets.user_id, Tweets.tweet_text, Tweets.creation_date FROM Tweets JOIN Users ON Tweets.user_id=Users.id WHERE Users.id=:id ORDER BY Tweets.id DESC");

        // @ToDo - przerobić na wyciąganie samych id Tweetów i odpalanie w pętli foreach loadTweetById()
        
        $ret = [];

        $result = $stmt->execute(['id' => $userId]); 

        if ($result !== false && $stmt->rowCount() != 0) {

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
    
    /**
     * Zwraca tablicę obiektów klasy Tweet - wszystkie tweety znajdujące się w bazie danych.
     * @param PDO $conn Połączenie z bazą danych
     * @return \Tweet
     */
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
    
    /**
     * Dodaje tweeta do bazy danych lub uaktualnia, jeżeli tweet z takim ID już istnieje.
     * @param PDO $conn Połączenie z bazą danych
     * @return boolean
     */
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
