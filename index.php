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
        <title>Something Like Twitter - Main Page</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="#">Something Like Twitter</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Page 1-1</a></li>
                                <li><a href="#">Page 1-2</a></li>
                                <li><a href="#">Page 1-3</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="page_register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="page_login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="jumbotron text-center">
            <h1>Welcome, dear Guest <span class="glyphicon glyphicon-sunglasses"></span></h1>
            <p>This page is a simple clone of twitter.
                Feel free and tweet something :)</p>
        </div>
        <div class="container">
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
