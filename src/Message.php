<?php

/**
 * Klasa do obsługi wiadomości przesyłanych pomiędzy użytkownikami
 */
class Message
{
    /**
     * Stała określająca ID obiektu nieistniejącego w bazie.
     */
    const NON_EXISTING_ID = -1;
    
    const MESSAGE_IS_READ = 1;
    
    const MESSAGE_IS_UNREAD = 0;
    
    /**
     *
     * @var int ID wiadomości
     */
    private $id;
    
    /**
     *
     * @var int ID nadawcy wiadomości
     */
    private $senderId;
    
    /**
     *
     * @var int ID odbiorcy wiadomości
     */
    private $recipientId;
    
    /**
     *
     * @var string Treść wiadomości
     */
    private $text;
    
    /**
     *
     * @var date Data stworzenia wiadomości
     */
    private $creationDate;
    
    /**
     *
     * @var int 0 - wiadomość nieprzeczytana, 1 - wiadomość przeczytana
     */
    private $isRead;
    
    /////////////////////////////////
    
    /**
     * Konstruktor ustawia ID na -1, isRead na 0 (wiadomość nieprzeczytana) a pozostałe atrybuty na puste stringi.
     */
    public function __construct()
    {
        $this->id = self::NON_EXISTING_ID;
        $this->senderId = self::NON_EXISTING_ID;
        $this->recipientId = self::NON_EXISTING_ID;
        $this->text = '';
        $this->creationDate = '';
        $this->isRead = self::MESSAGE_IS_UNREAD;
    }

    /////////////////////////////////
    
    /**
     * Zwraca ID wiadomości 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Zwraca ID nadawcy wiadomości
     * @return int
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Zwraca ID odbiorcy wiadomości
     * @return int
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Zwraca treść wiadomości
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Zwraca datę stworzenia wiadomości
     * @return date
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Zwraca 0, jeżeli wiadomość jest nieprzeczytana, lub 1, jeżeli została przeczytana
     * @return int
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
        
    /////////////////////////////////
    
    /**
     * Ustawia ID nadawcy wiadomości
     * @param int $senderId ID nadawcy wiadomości
     * @return $this
     */
    public function setSenderId($senderId)
    {
        $this->senderId = (int) $senderId;
        return $this;
    }

    /**
     * Ustawia ID odbiorcy wiadomości
     * @param int $recipientId ID odbiorcy wiadomości
     * @return $this
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = (int) $recipientId;
        return $this;
    }

    /**
     * Ustawia treść wiadomości
     * @param string $text Treść wiadomości
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string) $text;
        return $this;
    }

    /**
     * Ustawia datę stworzenia wiadomości
     * @param date $creationDate Data stworzenia wiadomości
     * @return $this
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = (string) $creationDate;
        return $this;
    }
    
    /**
     * Ustawia wiadomość jako przeczytaną
     * @return $this
     */
    public function setMessageAsRead()
    {
        $this->isRead = self::MESSAGE_IS_READ;
        return $this;
    }
    
    /**
     * Ustawia wiadomość jako nieprzeczytaną
     * @return $this
     */
    public function setMessageAsUnread()
    {
        $this->isRead = self::MESSAGE_IS_UNREAD;
        return $this;
    }

    /////////////////////////////////
    
    /**
     * Zwraca obiekt klasy Message - wiadomość o podanym ID.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $id ID wiadomości
     * @return \Message|null
     */
    static public function loadMessageById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Messages WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['sender_id'];
            $loadedMessage->recipientId = $row['recipient_id'];
            $loadedMessage->text = $row['message_text'];
            $loadedMessage->isRead = $row['is_read'];
            $loadedMessage->creationDate = $row['creation_date'];

            return $loadedMessage;
        }

        return null;
    }
    
    /**
     * Zwraca tablicę obiektów klasy Message - wszystkie wiadomości wysłane przez użytkownika o podanym ID.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $senderId ID nadawcy wiadomości
     * @return \Message
     */
    static public function loadAllMessagesBySenderId(PDO $conn, $senderId)
    {
        $stmt = $conn->prepare(
                  "SELECT Messages.id, Messages.sender_id, Messages.recipient_id, Messages.message_text, Messages.is_read, Messages.creation_date "
                . "FROM Messages JOIN Users ON Messages.sender_id=Users.id "
                . "WHERE Users.id=:id ORDER BY Messages.id DESC");
         
        $ret = [];

        $result = $stmt->execute(['id' => $senderId]); 

        if ($result !== false && $stmt->rowCount() != 0) {

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->recipientId = $row['recipient_id'];
                $loadedMessage->text = $row['message_text'];
                $loadedMessage->isRead = $row['is_read'];
                $loadedMessage->creationDate = $row['creation_date'];
                
                $ret[] = $loadedMessage;
            }
        }

        return $ret;
    }
    
    /**
     * Zwraca tablicę obiektów klasy Message - wszystkie wiadomości otrzymane przez użytkownika o podanym ID.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $recipientId ID odbiorcy wiadomości
     * @return \Message
     */
    static public function loadAllMessagesByRecipientId(PDO $conn, $recipientId)
    {
        $stmt = $conn->prepare(
                  "SELECT Messages.id, Messages.sender_id, Messages.recipient_id, Messages.message_text, Messages.is_read, Messages.creation_date "
                . "FROM Messages JOIN Users ON Messages.recipient_id=Users.id "
                . "WHERE Users.id=:id ORDER BY Messages.id DESC");
         
        $ret = [];

        $result = $stmt->execute(['id' => $recipientId]); 

        if ($result !== false && $stmt->rowCount() != 0) {

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->senderId = $row['sender_id'];
                $loadedMessage->recipientId = $row['recipient_id'];
                $loadedMessage->text = $row['message_text'];
                $loadedMessage->isRead = $row['is_read'];
                $loadedMessage->creationDate = $row['creation_date'];
                
                $ret[] = $loadedMessage;
            }
        }

        return $ret;
    }
    
    /**
     * Dodaje wiadomość do tablicy Messages w bazie danych, lub uaktualnia, jeżeli ID wiadomości jest różne od stałej NON_EXISTING_ID.
     * @param PDO $conn Połączenie z bazą danych
     * @return boolean
     */
    public function saveToDB(PDO $conn)
    {
        if ($this->id == self::NON_EXISTING_ID) {
            //Saving new message to DB
            $stmt = $conn->prepare(
                'INSERT INTO Messages(sender_id, recipient_id, message_text, is_read, creation_date) VALUES (:senderId, :recipientId, :text, :is_read, :creationDate)'
            );

            $result = $stmt->execute(
                    [
                        'senderId' => $this->senderId, 
                        'recipientId' => $this->recipientId, 
                        'text' => $this->text, 
                        'is_read' => $this->isRead, 
                        'creationDate' => $this->creationDate
                    ]
            );
            
            if ($result === true) {
                $this->id = $conn->lastInsertId();

                return true;
            }
        } else {
            //Updating message in DB
            $stmt = $conn->prepare(
                    'UPDATE Messages SET senderId=:senderId, recipientId=:recipientId, message_text=:text, creation_date=:creationDate, is_read=:read WHERE id=:id'
            );

            $result = $stmt->execute(
                    [
                        'senderId' => $this->senderId, 
                        'recipientId' => $this->recipientId, 
                        'text' => $this->text,
                        'is_read' => $this->isRead, 
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
