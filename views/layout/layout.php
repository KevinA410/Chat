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

    echo '
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Socket client script -->
    <script src="../js/socket_connect.js"> </script>
    ';

    header_end();

    content_begin();
    echo '
    <!-- User id -->
    <input type="text" value="'.$_SESSION['user']->getId().'" id="userId" hidden>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow">
        <div class="container-fluid">
            <!-- Slice connected panel -->
            <button class="btn d-inline-block d-lg-none" id="btn_slide">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#191919" class="bi bi-list"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>

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
                    .'&nbsp;<img src="../resources/profiles/'.$_SESSION['user']->getAvatar().'" width="30px" height="30px">
                </button>
                <ul class="dropdown-menu">
                    <li><a href="profile.php" class="dropdown-item">Profile</a></li>
                    <li>
                        <form id="form_logout" action="../database/controller/logoutController.php" method="POST">
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
    echo '
    <!-- Footer -->
    <div class="container-fluid bg-light">
        <p class="text-center mb-0 p-1">
            <small>&copy;Chat 2021. Todos los derechos reservados.</small>
        </p>
    </div>
    ';
    content_end();
}