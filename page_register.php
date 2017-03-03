<?php
require_once 'connection.php';
require 'src/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // @ToDo - validacja

    $user = new User();

    $user->setUsername($_POST['username']); //
    $user->setEmail($_POST['email']);
    $user->setPass($_POST['password']);

    $user->saveToDB($conn);
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
                </div>
            </div>    
    </body>
</html>

