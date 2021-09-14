<?php
require_once '../model/User.php';
require_once 'DBController.php';

session_start();

// Password doesn't match
if(!password_verify($_POST['password'], $_SESSION['user']->getPassword())) {
    header("Location: ../../views/profile.php?invalidPassword=1");
    return;
}

// Username already exists
$invalidUsername = DBController::selectUser('username', $_POST['username']);

if($_SESSION['user']->getUsername() <=> $_POST['username'] && $invalidUsername) { // Username already exists
    header("Location: ../../views/profile.php?invalidUsername=1");
    return;
}

// Email already registered
$invalidEmail = DBController::selectUser('email', $_POST['email']);

if($_SESSION['user']->getEmail() <=> $_POST['email'] && $invalidEmail) { //Email already registered
    header("Location: ../../views/profile.php?invalidEmail=1");
    return;
}

$_SESSION['user']->setUsername($_POST['username']); // Change username
$_SESSION['user']->setEmail($_POST['email']); // Change email

// User upload a picture
var_dump('avatar: ');
var_dump($_FILES['avatar']);
if($_FILES['avatar']['name']) {
    $saveDir = '../../resources/profiles/';
    $fileName = time() . basename($_FILES['avatar']['name']);
    $previous = $saveDir . $_SESSION['user']->getAvatar();

    move_uploaded_file($_FILES['avatar']['tmp_name'], $saveDir . $fileName); // Storage picture

    if($_SESSION['user']->getAvatar() <=> 'default.png' && file_exists($previous))
        unlink($previous); // Delete previous avatar

    $_SESSION['user']->setAvatar($fileName); // Change avatar
}

DBController::updateUser($_SESSION['user']);
header('Location: ../../views/profile.php');
