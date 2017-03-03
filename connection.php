<?php

//Stwórz poniżej odpowiednie zmienne z danymi do bazy
$host = "localhost";
$user = "root";
$pass = "coderslab";
$db = "twitter";
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

//Poniżej napisz kod łączący się z bazą danych
$conn = new PDO($dsn, $user, $pass);

//Sprawdzamy, czy połączenie się udało
if ($conn->errorCode() != null) {
    // die - exit z komunikatem
    die("Polaczenie nieudane. Blad: " .
            $conn->errorInfo()[2]);
}
echo("Polaczenie udane.<br><br>");
