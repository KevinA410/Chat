<?php
require_once '../model/User.php';
require_once 'DBController.php';

$user = DBController::selectUser('username', $_POST['username']);

if(!$user){ // User doesn't exist
    header("Location: ../../views/login.php?invalidUser=1");
    return;
}

// Incorrect password
if(!password_verify($_POST['password'], $user->getPassword())){
    header("Location: ../../views/login.php?invalidPassword=1");
    return;
}

session_start();
$_SESSION['user'] = $user;
header("Location: ../../views/home.php");