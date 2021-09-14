<?php
require_once '../model/User.php';
require_once 'DBController.php';

$invalidUsername = DBController::selectUser('username', $_POST['username']);

if($invalidUsername) { // Username already exists
    header("Location: ../../views/register.php?invalidUsername=1");
    return;
}

$invalidEmail = DBController::selectUser('email', $_POST['email']);

if($invalidEmail) { //Email already registered
    header("Location: ../../views/register.php?invalidEmail=1");
    return;
}

DBController::insertUser(User::factory(
    NULL,
    $_POST['username'],
    $_POST['email'],
    password_hash($_POST['password'], PASSWORD_DEFAULT),
    NULL,
    NULL
));

header("Location: ../../views/login.php");