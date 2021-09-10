<?php
function header_begin() 
{
    echo '
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat</title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
            integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"
            integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous">
        </script>

        <!-- Custom CSS -->
        <link rel="stylesheet" href="../css/main.css">
        ';
    }
    
function header_end()
{
    echo '
    </head>
    ';
}

function content_begin()
{
    echo '
    <body>
    ';
}

function content_end()
{
    echo '
    </body>
    </html>
    ';
}


function layout_begin(){
    include_once '../database/model/User.php';
    session_start();

    header_begin();
    header_end();

    content_begin();
    echo '
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../resources/icons/logo.png" alt="" width="30" height="24"
                    class="d-inline-block align-text-top">
                Chat
            </a>
            <!-- User options -->
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <!-- Avatar -->'
                    .$_SESSION['user']->getUsername()
                    .'<img src="../resources/profiles/'.$_SESSION['user']->getAvatar().'" width="30px" height="30px">
                </button>
                <ul class="dropdown-menu">
                    <li><a href="" class="dropdown-item">Profile</a></li>
                    <li>
                        <form action="../database/controller/logoutController.php" method="POST">
                            <input type="submit" name="logout" value="Logout" class="dropdown-item">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    ';

}

function layout_end(){
    content_end();
}