<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
} else {
    $visitorUserID = $_SESSION['user_id'];
    $userID = $visitorUserID; /** @ToDo: Zmienna na potrzeby NAV - do poprawienia */
}

require_once 'connection.php';
require 'src/User.php';
require 'src/Tweet.php';
require 'src/Message.php';

// z geta mamy ID
$pageUserID = $_GET['id'];
// walidacja

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    /** @ToDo: walidacja  */
    $message = new Message;
    
    $message->setSenderId($visitorUserID);
    $message->setRecipientId($pageUserID);
    $message->setText($_POST['text']);
    $message->setCreationDate(date('Y-m-d H:i:s'));
    
    /** @ToDo: Komunikat o wysłaniu wiadomości. */
}

$user = User::loadUserById($conn, $pageUserID);
$userTweets = Tweet::loadAllTweetsByUserId($conn, $pageUserID);
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
            <?php
            // Jeżeli oglądamy stronę innego użytkownika to powinniśmy mieć możliwość wysłania do niego wiadomości.
            if ($visitorUserID !== $pageUserID) {
                echo '<div class="row">';
                echo '<h3>Send Message</h3>';
                echo '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 message-send">';
                echo '<form action="" method="post" role="form">';
                echo '<div class="form-group">';
                echo '<p><textarea name="text" rows="4" class="form-control"></textarea></p>';
                echo '</div>';
                echo '<button type="submit" class="btn btn-default btn-send-message">Send</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                /** @ToDo: Komunikat o wysłaniu wiadomości. */
            }
            ?>
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
