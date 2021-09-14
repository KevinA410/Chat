<?php include_once 'layout/layout.php' ?>

<?php header_begin()?>
<?php header_end()?>

<?php content_begin()?>
<div id="main" class="d-flex justify-content-center align-items-center row">
    <div class="col-11 col-lg-5">
        <div class="card shadow p-3">
            <!-- Header -->
            <a href="login.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-arrow-left"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
            </a>
            <div class="text-center mb-4">
                <img id="login-brand" src="../resources/icons/logo.png" alt="">
                <h2 class="card-title text-muted">Chat</h2>
            </div>
            <!-- Form -->
            <form action="../database/controller/registerController.php" method="POST">
                <!-- Username -->
                <div class="form-group mb-3">
                    <label for="username" class="form-label text-muted">Username</label>
                    <input type="text" class="form-control" name="username" aria-describedby="usernamelHelp">
                    <?php if(isset($_REQUEST['invalidUsername'])): ?>
                    <div id="usernameHelp" class="form-text">This username already exists</div>
                    <?php endif ?>
                </div>
                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label text-muted">Email</label>
                    <input type="email" class="form-control" name="email" aria-describedby="emaillHelp">
                    <?php if(isset($_REQUEST['invalidEmail'])): ?>
                    <div id="emailHelp" class="form-text">Email already registered  </div>
                    <?php endif ?>
                </div>
                <!-- Password -->
                <div class="form-group mb-3">
                    <label for="password" class="form-label text-muted">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control pass-input" name="password" aria-describedby="passwordlHelp">
                        <span class="input-group-text pass-span"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                            </svg></span>
                    </div>
                    <div id="passwordHelp" class="form-text" hidden>Incorrect password</div>
                </div>
                <!-- Buttons -->
                <div class="d-flex justify-content-end mt-4">
                    <input type="submit" class="btn btn-success ml-auto" value="Register">
                </div>
            </form>
        </div>
    </div>
</div>
<?php content_end() ?>