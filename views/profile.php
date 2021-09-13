<?php include_once 'layout/layout.php' ?>

<?php layout_begin() ?>
<div id="main-adapted">
    <div id="center-cont" class="d-flex justify-content-center align-items-center row">
        <form action="../database/controller/updateUserController.php" method="POST" class="card shadow p-4 col-8">
            <div class="row">
                <!-- Avatar section -->
                <div class="col-5 d-flex justify-content-center align-items-start">
                    <div class="text-center">
                        <img src="../resources/profiles/<?php echo $_SESSION['user']->getAvatar() ?>" width="80%">
                        <div class="w-100"></div>
                        <label class="mt-3 label-input h6 d-block text-center"><b>Cambiar foto</b></label>
                        <input id="fichero" name="avatar" type="file" hidden />
                        <div class="d-flex justify-content-center">
                            <input id="btn-open" type="button" value="Seleccionar archivo" class="btn btn-secondary" />
                        </div>
                    </div>
                </div>
                <!-- Data section -->
                <div class="col-7">
                    <!-- Username -->
                    <div class="form-group mb-3">
                        <label for="username" class="form-label text-muted">Username</label>
                        <input type="text" class="form-control" name="username" aria-describedby="usernamelHelp" value="<?php echo $_SESSION['user']->getUsername() ?>">
                        <div id="usernameHelp" class="form-text" hidden>Invalid username</div>
                    </div>
                    <!-- IP address -->
                    <div class="form-group mb-3">
                        <label for="profile_address" class="form-label text-muted">IP Address</label>
                        <input type="text" class="form-control" id="profile_address" value="" disabled>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label text-muted">Email</label>
                        <input type="email" class="form-control" name="email" aria-describedby="emaillHelp" value="<?php echo $_SESSION['user']->getEmail() ?>">
                        <div id="emailHelp" class="form-text" hidden>Invalid username</div>
                    </div>
                    <!-- Password -->
                    <div class="form-group mb-3">
                        <label for="password" class="form-label text-muted">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password"
                                aria-describedby="passwordlHelp" placeholder="Enter your current password to confirm">
                            <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg></span>
                        </div>
                        <div id="passwordHelp" class="form-text" hidden>Incorrect password</div>
                    </div>
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end">
                        <input type="submit" class="btn btn-primary ml-auto" value="Update">
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<?php layout_end() ?>