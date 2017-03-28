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
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // getUserByEmail
    $user = User::loadUserByEmail($conn, $email);

    if ($user !== null) {

        // check password
        if (password_verify($pass, $user->getHashPass())) {

            // update user data in session
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_email'] = $user->getEmail();

            // redirect
            header('Location: index.php');
        } else {
            $errorMessage = 'Incorrect login and/or password!';
        }
    } else {
        $errorMessage = 'Incorrect login and/or password!';
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
    </head>
    <body>
        <div class="container">
            <div class="login">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <?php
                        if (isset($errorMessage)) {
                            echo $errorMessage;
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
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
                        <p><a href="index.php">Return to main page</a></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
