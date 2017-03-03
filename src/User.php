<?php

class User
{

    const NON_EXISTING_ID = -1;

    private $id;
    private $username;
    private $email;
    private $hashPass;

    public function __construct()
    {
        $this->id = self::NON_EXISTING_ID;
    }

    ////////////////////////////////

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHashPass()
    {
        return $this->hashPass;
    }

    /////////////////////////////////

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPass($pass)
    {
        $this->hashPass = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    //////////////////////////////////

    public function saveToDB(PDO $conn)
    {
        if ($this->id == self::NON_EXISTING_ID) {
            //Saving new user to DB
            $stmt = $conn->prepare(
                    'INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)'
            );

            $result = $stmt->execute(
                    ['username' => $this->username, 'email' => $this->email, 'pass' => $this->hashPass]
            );

            if ($result !== false) {
                $this->id = $conn->lastInsertId();

                return true;
            }
        } else {
            //Updating user in DB
            $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id'
            );

            $result = $stmt->execute(
                    ['username' => $this->username, 'email' => $this->email,
                        'hash_pass' => $this->hashPass, 'id' => $this->id]
            );

            if ($result === true) {

                return true;
            }
        }

        return false;
    }

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
