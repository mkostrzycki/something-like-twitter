<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
}

require_once 'config/connection.php';
require 'src/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** @ToDo: Filter received data */
    
    if (User::loadUserByEmail($conn, $_POST['email']) === null) {

        $user = new User();

        $user->setUsername($_POST['username']); //
        $user->setEmail($_POST['email']);
        $user->setPass($_POST['password']);

        $user->saveToDB($conn);

        // ustaw dane użytkownika w sesji
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['user_email'] = $user->getEmail();

        // redirect
        header('Location: index.php');
    } else {
        $errorMessage = 'User with this email address already exist!';
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Tweeter - User Register</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php
                    if (isset($errorMessage)) {
                        echo $errorMessage;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <form action="" method="post" role="form">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username">
                            <br>
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email">
                            <br>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                            <br>
                            <button>Register</button>
                        </form>
                    </div>
                    <p>Already registered? <a href="page_login.php">Log in here</a></p>
                    <p><a href="index.php">Return to main page</a></p>
                </div>
            </div>
        </div>
    </body>
</html>

