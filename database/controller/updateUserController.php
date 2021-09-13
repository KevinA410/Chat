<?php
require_once '../model/User.php';
require_once 'DBController.php';

session_start();

if(password_verify($_POST['password'], $_SESSION['user']->getPassword())) {
    if($_FILES['avatar']) {
        var_dump('true');
        $saveDir = '../../resources/profiles/';
        $fileName = time() . basename($_FILES['avatar']['name']);

        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $saveDir . $fileName)){
            var_dump('saved');
        }else{
            var_dump('filed');
        }

        if($_SESSION['user']->getAvatar() <=> 'default.png') {
            unlink($saveDir . $_SESSION['user']->getAvatar());
        }

        $_SESSION['user']->setAvatar($fileName);

    }

    $_SESSION['user']->setUsername($_POST['username']);
    $_SESSION['user']->setEmail($_POST['email']);

    DBController::updateUser($_SESSION['user']);
    header("Location: ../../views/profile.php");
}
