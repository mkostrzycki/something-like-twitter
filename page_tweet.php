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
require 'src/Tweet.php';
require 'src/Comment.php';

$tweetID = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /** @ToDo: walidacja  */
    $comment = new Comment();

    $comment->setUserId($userID);
    $comment->setTweetId($tweetID);
    $commentText = htmlspecialchars($_POST['text'], ENT_QUOTES);
    $comment->setText($commentText);
    $comment->setCreationDate(date('Y-m-d H:i:s'));

    $comment->saveToDB($conn);
}

$tweet = Tweet::loadTweetById($conn, $tweetID);
$comments = Comment::loadAllCommentsByTweetId($conn, $tweetID);
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
                    echo '<span class="tweet-username">' . '<a href="page_user.php?id='
                        . $tweet->getUserId() . '"><span class="glyphicon glyphicon-user"></span> '
                        . User::getUsernameById($conn, $tweet->getUserId()) . '</a></span>';
                    echo '<p>' . $tweet->getText() . '</p>';
                    echo '<span class="tweet-date"><span class="glyphicon glyphicon-calendar"></span> '
                        . $tweet->getCreationDate() . '</span>';
                    echo '</div>';
                    ?>
                </div>
            </div>
            <div class="row comment-send">
                <h3>Add Comment</h3>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 comment-send">
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <p><textarea name="text" rows="4" class="form-control"<?php echo ($userID !== null) ? '' : 'disabled'; ?>></textarea></p>
                        </div>
                        <button type="submit" class="btn btn-default btn-comment" <?php echo ($userID !== null) ? '' : 'disabled'; ?>>Add</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <h3>Comments</h3>
                <div class="comments">
                    <?php
                    foreach ($comments as $comment) {
                        echo '<div class="row">';
                        echo '<div class="comment col-xs-12 col-sm-8 col-md-8 col-lg-6">';
                        echo '<span class="tweet-username">' . '<a href="page_user?id=' . $comment->getUserId()
                            . '"><span class="glyphicon glyphicon-user"></span> '
                            . User::getUsernameById($conn, $comment->getUserId()) . '</a></span>';
                        echo '<p>' . $comment->getText() . '</p>';
                        echo '<span class="tweet-date"><span class="glyphicon glyphicon-calendar"></span> '
                            . $comment->getCreationDate() . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
