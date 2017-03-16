<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
}

require_once 'connection.php';
require 'src/User.php';
require 'src/Tweet.php';

// z geta mamy ID
$userID = $_GET['id'];
// walidacja

$user = User::loadUserById($conn, $userID);
$userTweets = Tweet::loadAllTweetsByUserId($conn, $userID);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Twitter - User Page</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--NAV START-->
        <?php
        include('includes/nav.php');
        ?>
        <!--NAV END-->
        <div class="container">
            <!--dane użytkownika-->
            <div class="row">
                <h3>User Info</h3>
                <div class="user-info">
                    <?php
                    echo '<p>' . $user->getUsername() . '</p>';
                    echo '<p>' . $user->getEmail() . '</p>';
                    ?>
                </div>
            </div>
            <!--jeżeli oglądamy stronę innego użytkownika to powinniśmy mieć możliwość wysłania do niego wiadomości-->
            <div class="row">
                <h3>All Tweets by User</h3>
                <div class="tweets">
                    <?php
                    foreach ($userTweets as $tweet) {
                        echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
                        echo '<div class="tweet">';
                        echo '<p>' . $tweet->getText() . '</p>';
                        echo '<span class="tweet-date">' . $tweet->getCreationDate() . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
