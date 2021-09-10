<?php
require_once 'DBController.php';

$user = DBController::selectUser('username', $_POST['username']);

if(!$user){
    echo "This user doesn't exist";
    return;
}

if(!password_verify($_POST['password'], $user->getPassword())){
    echo "User and password doesn't match";
    return;
}

session_start();
$_SESSION['user'] = $user;
header("Location: ../../views/home.php");