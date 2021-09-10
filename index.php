<?php
session_start();

if(isset($_SESSION['user'])){
    header("Location: views/home.php");
    return;
}

session_destroy();

header("Location: views/login.php");
return;
?>