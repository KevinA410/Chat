<?php
require_once '../model/User.php';
require_once 'DBController.php';

session_start();

if(password_verify($_POST['password'], $_SESSION['user']->getPassword())){
    $_SESSION['user']->setUsername($_POST['username']);
    $_SESSION['user']->setEmail($_POST['email']);

    DBController::updateUser($_SESSION['user']);
    header("Location: ../../views/profile.php");
}
