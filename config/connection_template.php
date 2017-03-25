<?php

/**
 * Fill in the variable values with your data 
 * and then rename the file to connection.php.
 */

$host = "";
$user = "";
$pass = "";
$dbName = "";
$dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";

$conn = new PDO($dsn, $user, $pass);

if ($conn->errorCode() != null) {
    die("Something goes wrong :( ERROR: " .
            $conn->errorInfo()[2]);
}
