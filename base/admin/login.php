<?php

session_start();

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

