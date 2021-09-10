<?php include_once '../database/model/User.php' ?>
<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"
        integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous">
    </script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../resources/icons/logo.png" alt="" width="30" height="24"
                    class="d-inline-block align-text-top">
                Chat
            </a>

            <!-- User options -->
            <div class="btn-group dropstart">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <!-- Avatar -->
                    Kevin A.
                    <img src="../resources/profiles/<?php echo $_SESSION['user']->getAvatar() ?>" width="30px" height="30px">
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button">Profile</button></li>
                    <li>
                        <form action="../database/controller/logoutController.php" method="POST">
                            <input type="submit" name="logout" value="Logout" class="dropdown-item">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>