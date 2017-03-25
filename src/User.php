<?php

/**
 * Klasa do obsługi użytkowników.
 */
class User
{

    /**
     * Stała określająca ID obiektu nieistniejącego w bazie.
     */
    const NON_EXISTING_ID = -1;

    /**
     *
     * @var int ID obiektu
     */
    private $id;
    
    /**
     *
     * @var string Nazwa użytkownika
     */
    private $username;
    
    /**
     *
     * @var string Adres e-mail użytkownika
     */
    private $email;
    
    /**
     *
     * @var string Hash wygenerowany na podstawie hasła użytkownika
     */
    private $hashPass;
    
    ////////////////////////////////

    /**
     * Konstruktor ustawia ID na -1 a pozostałe atrybuty na puste stringi.
     */
    public function __construct()
    {
        $this->id = self::NON_EXISTING_ID;
        $this->username = '';
        $this->email = '';
        $this->hashPass = '';
    }

    ////////////////////////////////

    /**
     * Zwraca ID użytkownika
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Zwraca nazwę użytkownika
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Zwraca adres e-mail użytkownika
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Zwraca hash wygenerowany z hasła użytkownika
     * @return string
     */
    public function getHashPass()
    {
        return $this->hashPass;
    }

    /////////////////////////////////

    /**
     * Ustawia nazwę użytkownika
     * @param string $username Nazwa użytkownika
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    /**
     * Ustawia adres e-mail użytkownika
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     * Setter hasła. Przyjmuje hasło w postaci stringa, hashuje je i przypisuje
     * do atrybutu $hashPass.
     * @param string $pass Hasło użytkownika
     * @return $this
     */
    public function setPass($pass)
    {
        $this->hashPass = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    //////////////////////////////////

    /**
     * Zapisuje nowego użytkownika do bazy lub aktualizuje istniejącego.
     * @param PDO $conn Połączenie z bazą danych
     * @return boolean
     */
    public function saveToDB(PDO $conn)
    {
        if ($this->id == self::NON_EXISTING_ID) {
            //Saving new user to DB
            $stmt = $conn->prepare(
                    'INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)'
            );

            $result = $stmt->execute(
                    [
                        'username' => $this->username, 
                        'email' => $this->email, 
                        'pass' => $this->hashPass
                    ]
            );

            if ($result === true) {
                $this->id = $conn->lastInsertId();

                return true;
            }
        } else {
            //Updating user in DB
            $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id'
            );

            $result = $stmt->execute(
                    [
                        'username' => $this->username, 
                        'email' => $this->email,
                        'hash_pass' => $this->hashPass, 
                        'id' => $this->id
                    ]
            );

            if ($result === true) {

                return true;
            }
        }

        return false;
    }

    /**
     * Wczytuje z bazy danych użytkownika o podanym ID i zwraca obiekt User lub null, jeżeli podanego ID nie ma w bazie.
     * @param PDO $conn Połączenie z bazą danych
     * @param int $id ID użytkownika
     * @return \User|null
     */
    static public function loadUserById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];

            return $loadedUser;
        }

        return null;
    }
    
    /**
     * Wczytuje z bazy danych użytkownika o podanym adresie email i zwraca obiekt klasy User lub null, jeżeli podanego adresu email nie ma w bazie.
     * @param PDO $conn Połączenie z bazą danych
     * @param string $email Adres email użytkownika
     * @return \User|null
     */
    static public function loadUserByEmail(PDO $conn, $email)
    {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];

            return $loadedUser;
        }

        return null;
    }

    /**
     * Pobiera wszystkich użytkowników z bazy danych i zwraca tablicę z obiektami klasy User.
     * @param PDO $conn Połączenie z bazą danych
     * @return \User Tablica obiektów User
     */
    static public function loadAllUsers(PDO $conn)
    {
        $sql = "SELECT * FROM Users";

        $ret = [];

        $result = $conn->query($sql);

        if ($result !== false && $result->rowCount() != 0) {

            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];
                
                $ret[] = $loadedUser;
            }
        }

        return $ret;
    }
    
    /**
     * Zwraca username użytkownika o wskazanym ID
     * @param PDO $conn Połączenie z bazą danych
     * @param int $id ID użytkownika
     * @return string|null
     */
    static public function getUsernameById(PDO $conn, $id)
    {
        $stmt = $conn->prepare('SELECT username FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['username'];
        }

        return null;
    }

    /**
     * Usuwa użytkownika z bazy danych
     * @param PDO $conn Połączenie z bazą danych
     * @return boolean
     */
    public function delete(PDO $conn)
    {
        if ($this->id != self::NON_EXISTING_ID) {

            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);

            if ($result === true) {

                $this->id = -1;

                return true;
            }

            return false;
        }

        return true;
    }

}
