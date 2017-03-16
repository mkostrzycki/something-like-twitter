<?php
session_start();
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['user_email']);
    session_destroy();
    
    header('Location: index.php');
} else {
    header('Location: index.php');
}
