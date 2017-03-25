<?php
session_start();
if (
           isset($_SESSION['user_id']) 
        && isset($_SESSION['username']) 
        && isset($_SESSION['user_email'])
) {
    $userID = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $userEmail = $_SESSION['user_email'];
} else {
    $userID = null;
    $username = null;
    $userEmail = null;
}

require_once 'config/connection.php';
require 'src/User.php';
require 'src/Tweet.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** @ToDo: walidacja  */
    $tweet = new Tweet;

    $tweet->setUserId($userID); //
    $tweet->setText($_POST['text']);
    $tweet->setCreationDate(date('Y-m-d H:i:s'));

    $tweet->saveToDB($conn);
}

$tweets = Tweet::loadAllTweets($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Twitter - Main Page</title>
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
        <div class="jumbotron text-center">
            <h1>Welcome, dear <?php echo ($username !== null) ? $username : 'Guest'; ?> <span class="glyphicon glyphicon-sunglasses"></span></h1>
            <p>This page is a simple clone of twitter.
                Feel free and tweet something&nbsp;:)</p>
        </div>
        <div class="container">
            <div class="row tweet-send">
                <h3>Add Tweet</h3>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 tweet-send">
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <p><textarea name="text" rows="4" class="form-control"<?php echo ($userID !== null) ? '' : 'disabled'; ?>></textarea></p>
                        </div>
                        <button type="submit" class="btn btn-default btn-tweet" <?php echo ($userID !== null) ? '' : 'disabled'; ?>>Add</button>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="tweet-instruction">
                        <!--Inny komunikat zależnie od tego czy zalogowany-->
                        Myśmy więc on jest ta: każda realność, która swoją kulturę, wyjść z części. Ale czyżby w rzeczy szczęśliwości doprowadzić. My możemy się niezgadza z niej płynącej szczęśliwości tej dostąpić miało; gdyby więc też to tedy?
                    </div>
                </div>
            </div>
            <div class="row">
                <h3>All Tweets</h3>
                <div class="tweets">
                    <?php
                    foreach ($tweets as $tweet) {
                        echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
                        echo '<div class="tweet">';
                        echo '<p>' . $tweet->getText() . '</p>';
                        echo '<span class="tweet-date">' . $tweet->getCreationDate() . '</span>';
                        echo '<span class="tweet-username">' . '<a href="page_user.php?id=' . $tweet->getUserId() . '">' 
                                . User::getUsernameById($conn, $tweet->getUserId()) . '</a></span>';
                        echo '<a href="page_tweet.php?id=' . $tweet->getId() . '" class="open-tweet">open tweet >>></a>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
