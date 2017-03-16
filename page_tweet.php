<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
} else {
    $userID = $_SESSION['user_id'];
}

require_once 'connection.php';
require 'src/User.php';
require 'src/Tweet.php';

// z geta mamy ID
$tweetID = $_GET['id'];
// walidacja

$tweet = Tweet::loadTweetById($conn, $tweetID)
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Twitter - Tweet Page</title>
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
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
                    <?php
                    echo '<div class="tweet">';
                    echo '<p>' . $tweet->getText() . '</p>';
                    echo '<span class="tweet-date">' . $tweet->getCreationDate() . '</span>';
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
