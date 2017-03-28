<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
} else {
    $userID = $_SESSION['user_id'];
}

require_once 'config/connection.php';
require 'src/User.php';

$user = User::loadUserById($conn, $userID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** 
     * @ToDo: Filter received data 
     * @ToDo: Add error messages 
     */
    
    $pass = $_POST['old-password'];
    if (password_verify($pass, $user->getHashPass())) {

        $somethingChange = false;

        $newUsername = trim($_POST['username']);
        if (strlen($newUsername) > 0 && $newUsername != $user->getUsername()) {
            $user->setUsername($newUsername);
            $somethingChange = true;
        }

        $newEmail = trim($_POST['email']);
        if (strlen($newEmail) > 0 && $newEmail != $user->getEmail()) {
            $user->setEmail($newEmail);
            $somethingChange = true;
        }

        $newPass = trim($_POST['new-password']);
        $retypedNewPass = trim($_POST['retyped-new-password']);
        if (strlen($newPass) > 0 && $newPass == $retypedNewPass) {
            $user->setPass($newPass);
            $somethingChange = true;
        }

        if ($somethingChange) {
            $user->saveToDB($conn);

            // update user data in session
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_email'] = $user->getEmail();

            $successMessage = 'New user data has been successfully saved.';
        }
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
        <title>Something Like Twitter - User Edit Page</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <?php
                    if (isset($successMessage)) {
                        echo $successMessage;
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <form action="" method="post" role="form">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="<?php echo $user->getUsername(); ?>">
                            <br>
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" value="<?php echo $user->getEmail(); ?>">
                            <br>
                            <label for="password">Old password</label>
                            <input type="password" name="old-password" id="old-password">
                            <br>
                            <label for="password">New password</label>
                            <input type="password" name="new-password" id="new-password">
                            <br>
                            <label for="password">Retype new password</label>
                            <input type="password" name="retyped-new-password" id="retyped-new-password">
                            <button>Save</button>
                        </form>
                    </div>
                    <p><a href="index.php">Return to main page</a></p>
                </div>
            </div>
        </div>
    </body>
</html>
