<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
}

require_once 'connection.php';
require 'src/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** @ToDo: walidacja */
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // getUserByEmail
    $user = User::loadUserByEmail($conn, $email);
    var_dump($user);

    if ($user !== null) {

        // check password
        if (password_verify($pass, $user->getHashPass())) {

            // ustaw dane użytkownika w sesji
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_email'] = $user->getEmail();

            // redirect
            header('Location: index.php');
        } else {
            $message = 'Incorrect login and/or password!';
        }
    } else {
        $message = 'Incorrect login and/or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Twitter - Login Page</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <form action="" method="post" role="form">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email">
                            <br>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                            <br>
                            <button>Log in</button>
                        </form>
                    </div>
                    <p>You are not registered? <a href="page_register.php">Create an account</a></p>
                </div>
            </div>
        </div>
    </body>
</html>
