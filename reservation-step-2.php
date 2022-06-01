<?php
    include_once ("include/dbconnect.php");

    $config_payvalue = 32000;
    $penalty_fee = 0.0;
    $sql = "select * from merchant where MId='" . $_SESSION['MId'] . "'";
    $result = mysqli_query($conn,$sql);

    while($row = $result->fetch_assoc()) 
    {
        $biz_name = $row['Businessname'];
        $payment_tag = $row['PaymentTag'];
        $config_duration = $row['Configduration'];
        $config_payvalue = $row['Configpayvalue'];
        $penalty_fee = $row['Penaltyfee'];
    }

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Enjoy! purchases across social media, classifieds or retail shops with control to determine when and how you receive your good or service.">
    <meta name="author" content="Monster Studios">
    <meta name="keywords" content="Streetzwyze, secure payment, buyer proctection, seller proctection, secure exchange">
    <title>Streetzwyze | Reservation</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="" sizes="180x180">
    <link rel="icon" href="" sizes="32x32" type="image/png">
    <link rel="icon" href="" sizes="16x16" type="image/png">
    <link rel="icon" href="">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;700&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link href="css/nivo_lightbox_themes/default/default.css" rel="stylesheet" media="screen">
    <link href="css/navbar-fixed-left.min.css" rel="stylesheet" media="screen">
    <!-- Custom styles for this template -->
    <link href="css/jquery.mmenu.all.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">

    <style>
        .error_Msg{ 
            color:#fa4b2a; 
            padding-left: 10px; 
        } 
    </style>
</head>

<body class="p-md-0">
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
                   <li class="nav-item active">
                        <a href="reservation.php" class="nav-link"><span><img src="img/dashboard/Reservation-action.gif"></span></a>
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
           <li class="nav-item active">
                <a href="reservation.php" class="nav-link"><span><img src="img/dashboard/Reservation-action.gif"></span></a>
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
    <main>
        <nav class="navbar navbar-expand-md navbar-inner navbar-light bg-light d-none d-md-flex">
            <a class="navbar-brand" href="index.php"><img src="img/st-logo.png" class="img-fluid d-none"></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item profile-badge">
                        <a class="nav-link" href="#">
                            <span id="profile-progress">
                                <img src="//placehold.it/50x50" class="rounded-circle"></span>
                            </span>
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
            </div>
        </nav>
        <div class="container-fluid mx-0">
            <div class="jumbotron m-0 px-0">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row d-none d-sm-flex title-bar">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; 500,000</h5>
                                        </div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; 500,000</h5>
                                        </div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; 500,000</h5>
                                        </div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; 500,000</h5>
                                        </div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-lg-5">
                            <div class="col-lg-6 offset-lg-3 my-3">
                                <h2>Reservation</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-6 offset-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="st-form" id="reserve_form2" method="post">
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                                </div>
                                                <input type="text" id="order_amount" name="order_amount" class="form-control" placeholder="Order Amount">
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="order_error"> Error! Order amount can not be empty
                                                </p>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input type="text" id="reserve_tag" name="reserve_tag" class="form-control" placeholder="Reservation Tag">
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="reserve_error"> Error! Reserve tag can not be empty
                                                </p>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" id="customer_phone" name="customer_phone" class="form-control" placeholder="Customer Mobile" />
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="customer_error"> Error! Customer phone can not be empty
                                                </p>
                                            </div>
                                            <p class="text-right">
                                                <input type="hidden" id="reservation_date" name="reservation_date" class="form-control" value="<?php echo $_POST['reservation_date']; ?>" />
                                                <input type="hidden" id="delivery_duration" name="delivery_duration" class="form-control" value="<?php echo $_POST['delivery_duration']; ?>" />
                                                <button type="submit" id="btn_generate" class="btn btn-danger btn-block">Generate</button>
                                            </p>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="share-btn text-right">
                                                        <a href="#" class="d-none d-lg-inline-block"><i class="fab fa-facebook-f"></i></a>
                                                        <a href="#" class="d-sm-none d-inline-block"><i class="fab fa-facebook-messenger"></i></a>
                                                        <a href="#" class="d-sm-none d-inline-block"><i class="fab fa-whatsapp"></i></a>
                                                        <a href="#" class="d-none d-lg-inline-block"><i class="fas fa-envelope"></i></a>
                                                        <a href="#"><i class="fas fa-paste"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="form-helper p-4 mb-4">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua.
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 pl-0">
                        <div id="adv-slider" class="owl-carousel d-none d-lg-block">
                            <div class="item">
                                <a href="#">
                                    <img src="//placehold.it/200x800" class="img-fluid">
                                </a>
                            </div>
                            <div class="item">
                                <a href="#">
                                    <img src="//placehold.it/200x800" class="img-fluid">
                                </a>
                            </div>
                        </div>
                        <div class="float-nav d-block d-sm-none">
                            <a href="#" class="menu-btn">
                                <ul>
                                    <li class="line"></li>
                                    <li class="line"></li>
                                    <li class="line"></li>
                                    <li><i class="fas fa-bell"></i><span class="badge badge-success">3</span></li>
                                </ul>
                            </a>
                        </div>
                        <div class="main-nav">
                            <ul>
                                <li class="card">
                                    <div class="card-body">
                                        <div class="float-left"><h5 class="card-title pt-3">&#8358; 500,000</h5></div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </li>
                                <li class="card">
                                    <div class="card-body">
                                        <div class="float-left"><h5 class="card-title pt-3">&#8358; 500,000</h5></div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </li>
                                <li class="card">
                                    <div class="card-body">
                                        <div class="float-left"><h5 class="card-title pt-3">&#8358; 500,000</h5></div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </li>
                                <li class="card">
                                    <div class="card-body">
                                        <div class="float-left"><h5 class="card-title pt-3">&#8358; 500,000</h5></div>
                                        <div class="float-right"><img src="//placehold.it/50x50" class="img-responsive"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="js/jquery.mmenu.all.js"></script>
    <script src="js/dashboard.js"></script>
</body>

</html>

<script language="javascript">
    $(document).ready(function(){
        $(".error_Msg").hide();
        $("#btn_generate").click(function(e){
            var form = $("form#reserve_form2");

            var biz_name = "<?php echo $biz_name;?>";
            var payment_tag = "<?php echo $payment_tag;?>";
            var config_duration = eval(<?php echo $config_duration;?>);
            var config_payvalue = eval(<?php echo $config_payvalue;?>);
            var penalty_fee = eval(<?php echo $penalty_fee;?>);
            
            var order_amount = $("#order_amount").val();
            var reserve_tag = $("#reserve_tag").val();
            var delivery_duration = $("#delivery_duration").val();
            var customer_phone = $("#customer_phone").val();
            var regex_phone = /^[0-9-+]+$/;

            if (biz_name == "")
            {
                alert("Business name is empty now.");
                return;
            }

            if (payment_tag == "")
            {
                alert("Account number and CBN code are empty now.");
                return;
            }

            if (order_amount == '' && (eval(order_amount) < 6500 || eval(order_amount) > config_payvalue))
            {
                $("#order_error").show();
                e.preventDefault();
                return;
            }
            else
            {
                $("#order_error").hide();
            }

            if (penalty_fee >= 1)
            {
                alert("The penalty fee must be less than 1 or null.");
                return;
            }

            if (reserve_tag == '')
            {
                $("#reserve_error").show();
                e.preventDefault();
                return;
            }
            else
            {
                $("#reserve_error").hide();
            }

            if (delivery_duration == '' || delivery_duration > config_duration)
            {
                $("#duration_error").show();
                event.preventDefault();
                return;
            }
            else
            {
                $("#duration_error").hide();
            }

            if (!regex_phone.test(customer_phone))
            {
                $("#reserve_error").show();
                e.preventDefault();
                return;
            }
            else
            {
                $("#reserve_error").hide();
            }

            form.attr("action", "admin-seller/reservation.php");
            form.submit();
        });
    });
</script>