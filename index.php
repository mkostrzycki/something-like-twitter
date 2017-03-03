<?php
require_once 'connection.php';
require 'src/User.php';
require 'src/Tweet.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tweet = new Tweet;

    $tweet->setUserId(1); //
    $tweet->setText($_POST['text']);
    $tweet->setCreationDate(date('Y-m-d H:i:s'));

    $tweet->saveToDB($conn);
}

$tweets = Tweet::loadAllTweets($conn);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Tweeter - Main Page</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="row nav">
                <nav>
                    <ul>
                        <li><a href="#"><b>Home</b></a></li>
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                    </ul>
                    <div class="login-btn">
                        <a href="page_register.php">Sign Up</a>
                        <a href="page_login.php">Sign In</a>
                    </div>
                </nav>
            </div>
            <div class="row tweet-send">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <form action="" method="post" role="form">
                            <p><textarea name="text"></textarea></p>
                            <p><button>Send</button></p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                
                    <?php
                    foreach ($tweets as $tweet) {
                        echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 tweet">';
                        echo '<p>' . $tweet->getText() . '</p>';
                        echo '<span class="tweet-date">' . $tweet->getCreationDate() . '</span>';
                        echo '</div>';
                    }
                    ?>
               
            </div>
        </div>
    </body>
</html>