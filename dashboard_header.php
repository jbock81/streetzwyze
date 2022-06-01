<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>     
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-danger fixed-left">
            <a class="navbar-brand mb-lg-3 d-none d-md-block" href="index.php">Menu</a>
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav d-md-none">
                <li class="nav-item profile-badge">
                    <a class="nav-link" href="#">
                        <img src="//placehold.it/50x50" class="rounded-circle"></span>
                    </a>
                </li>
                <li class="nav-item dropdown profile-badge">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        Welcome, John Doe
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="edit-profile.php">Edit Profile</a>
                        <a class="dropdown-item" href="change-password.php">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Logout</a>
                    </div>
                </li>
            </ul>
            <div class="navbar-collapse offcanvas-collapse d-none">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="reservation.php" class="nav-link"><span><img src="img/dashboard/Reservation.png"></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="orders.php" class="nav-link"><span><img src="img/dashboard/Orders.png"></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="disbursements.php" class="nav-link"><span><img src="img/dashboard/Disbursement.png"></span></a>
                    </li>
                    <li class="nav-item alrt">
                        <a href="penalty-fees.php" class="nav-link"><span><img src="img/dashboard/Penalty-fee-idle.png"></span></a>
                    </li>
                    <li class="nav-item">
                        <a href="referrals.php" class="nav-link"><span><img src="img/dashboard/Referral.png"></span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <nav id="mobilemenu">
        <ul>
            <li class="nav-item">
                <a class="nav-link close-panel p-0"><img src="img/arrows_remove.svg"></a>
            </li>
            <li class="nav-item">
                <a href="reservation.php" class="nav-link"><span><img src="img/dashboard/Reservation.png"></span></a>
            </li>
            <li class="nav-item">
                <a href="orders.php" class="nav-link"><span><img src="img/dashboard/Orders.png"></span></a>
            </li>
            <li class="nav-item">
                <a href="disbursements.php" class="nav-link"><span><img src="img/dashboard/Disbursement.png"></span></a>
            </li>
            <li class="nav-item alrt">
                <a href="penalty-fees.php" class="nav-link"><span><img src="img/dashboard/Penalty-fee-idle.png"></span></a>
            </li>
            <li class="nav-item">
                <a href="referrals.php" class="nav-link"><span><img src="img/dashboard/Referral.png"></span></a>
            </li>
        </ul>
    </nav>