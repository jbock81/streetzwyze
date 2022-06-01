<?php
    if (isset($_GET['email']) && !empty($_GET['email']))
        $email = $_GET['email'];
    else
        $email = "";
    
    $signup_url = "seller-signup.php";
    /*if (isset($_GET['role']) && !empty($_GET['role']))
    {
        $role = $_GET['role'];
        if ($role == "merchant")
            $signup_url = "merchant-signup.php";
    }*/
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Enjoy! purchases across social media, classifieds or retail shops with control to determine when and how you receive your good or service.">
    <meta name="author" content="Monster Studios">
    <meta name="keywords" content="Streetzwyze, secure payment, buyer proctection, seller proctection, secure exchange">
    <title>Streetzwyze | Sign In</title>
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
    <link rel="stylesheet" href="css/nivo-lightbox.min.css" />
    <link href="css/nivo_lightbox_themes/default/default.css" rel="stylesheet" media="screen">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive-main.css" rel="stylesheet">
</head>

<body class="p-0">
    <main role="main">
        <div class="container-fluid w-100">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-12 login_bg d-none d-sm-block">
                    <a class="navbar-brand" href="index.php"><img src="img/st-white.png" class="img-fluid"></a>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-12 login_content">
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="navbar-brand d-block d-sm-none" href="index.php"><img src="img/st-white.png" class="img-fluid"></a>
                            <h2>Get more things done</h2>
                            <h3>Streetzwyze Platform</h3>
                            <form method="post" id="signin_form" action="admin-seller/login.php">
                                <p>Access to the most powerfull tool in the entire design and web industry.</p>
                                <div class="form-group pt-4">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                      </div>
                                      <input type="text" name="email" value="<?php echo $email;?>" class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                      </div>
                                      <input type="password" name="password" class="form-control" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-light mr-sm-3">Login</button> -or-
                                    <a href="<?php echo $signup_url;?>" class="btn btn-link mr-sm-3 text-light">Register</button>
                                    <a href="forgotten-password.php" class="btn btn-link text-light pr-0 float-right">Forgot Password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/nivo-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/app.js"></script>
</body>

</html>