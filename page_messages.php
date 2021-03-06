<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect
    header('Location: index.php');
}

require_once 'config/connection.php';
require 'src/User.php';
require 'src/Message.php';

$userID = $_GET['id'];

if ($userID !== $_SESSION['user_id']) {
    // redirect
    header('Location: index.php');
}

$sentMessages = Message::loadAllMessagesBySenderId($conn, $userID);
$receivedMessages = Message::loadAllMessagesByRecipientId($conn, $userID);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Something Like Twitter - Messages</title>
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
                <h3>Received Messages</h3>
                <div class="messages">
                    <?php
                    foreach ($receivedMessages as $message) {
                        echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
                        echo '<div class="message">';
                        echo '<p>Message from: ';
                        echo '<span class="message-username">' . '<a href="page_user.php?id='
                             . $message->getSenderId() . '"><span class="glyphicon glyphicon-user"></span> '
                             . User::getUsernameById($conn, $message->getSenderId()) . '</a></span>';
                        echo '</p>';
                        echo '<span class="message-date"><span class="glyphicon glyphicon-calendar"></span> ' 
                             . $message->getCreationDate() . '</span>';
                        echo '<span class="open-message">' . '<a href="page_message.php?id=' 
                             . $message->getId() . '"><span class="glyphicon glyphicon-hand-right"></span> open</a></span>';
                        if ($message->getIsRead() == $message::MESSAGE_IS_UNREAD) {
                            echo '<a href="page_message.php?id=' 
                            . $message->getId() 
                            . '"><span class="glyphicon glyphicon-envelope message-unread"></span></a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <h3>Sent Messages</h3>
                <div class="messages">
                    <?php
                    foreach ($sentMessages as $message) {
                        echo '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">';
                        echo '<div class="message">';
                        echo '<p>Message to: ';
                        echo '<span class="message-username">' . '<a href="page_user.php?id='
                             . $message->getRecipientId() . '"><span class="glyphicon glyphicon-user"></span> '
                             . User::getUsernameById($conn, $message->getRecipientId()) . '</a></span>';
                        echo '</p>';
                        echo '<span class="message-date"><span class="glyphicon glyphicon-calendar"></span> ' 
                             . $message->getCreationDate() . '</span>';
                        echo '<span class="open-message">' . '<a href="page_message.php?id=' 
                             . $message->getId() . '"><span class="glyphicon glyphicon-hand-right"></span> open</a></span>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
