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

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Custom CSS -->
        <link rel="stylesheet" href="../css/main.css">

        <!--General Scripts -->
        <script src="../js/show_password_toggle.js" defer></script>
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

    if(!isset($_SESSION['user'])) {
        header('Location: login.php');
        return;
    }

    header_begin();

    echo '
    <!-- Socket client script -->
    <script src="../js/socket_connect.js"> </script>
    ';

    header_end();

    content_begin();
    echo '
    <!-- User id -->
    <input type="text" value="'.$_SESSION['user']->getId().'" id="userId" hidden>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow navbar-expand">
        <div class="container-fluid">
            <div>
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
            </div>
            <!-- Sections -->
            <ul class="navbar-nav ms-auto me-3">
                <!-- Private -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="home.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                    <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                  </svg>
                    </a>
                </li>
                <!-- Chat rooms -->
                <li class="nav-item">
                    <a class="nav-link" href="rooms.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                  </svg>
                    </a>
                </li>
            </ul>
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