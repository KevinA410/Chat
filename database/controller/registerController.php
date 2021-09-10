<?php
require_once 'DBController.php';

DBController::insertUser(User::factory(
    NULL,
    $_POST['username'],
    $_POST['email'],
    password_hash($_POST['password'], PASSWORD_DEFAULT),
    NULL,
    NULL
));

header("Location: ../../views/login.php");