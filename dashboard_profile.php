        <nav class="navbar navbar-expand-md navbar-inner navbar-light bg-light d-none d-md-flex">
            <a class="navbar-brand" href="index.php"><img src="img/st-logo.png" class="img-fluid"></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item profile-badge">
                        <a class="nav-link" href="#">
                            <span id="profile-progress">
                                <img src="<?php echo $photo;?>" width="60px" height="60px" class="rounded-circle"></span>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown profile-badge">
                        <a id="a_welcome" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            Welcome, <?php echo $str_fullname; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="edit-profile.php">Edit Profile</a>
                            <a class="dropdown-item" href="change-password.php">Change Password</a>
                            <a class="dropdown-item" href="request-support.php">Request Support</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="admin-seller/logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>