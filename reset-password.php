<?php
    if (isset($_GET['token']) && !empty($_GET['token']))
        $token = $_GET['token'];
    else
        $token = "";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Enjoy! purchases across social media, classifieds or retail shops with control to determine when and how you receive your good or service.">
    <meta name="author" content="Monster Studios">
    <meta name="keywords" content="Streetzwyze, secure payment, buyer proctection, seller proctection, secure exchange">
    <title>Streetzwyze | Sign Up</title>
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
    <style>
        .error_Msg{ 
            color:#fa4b2a; 
            padding-left: 10px; 
        } 
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php"><img src="img/st-logo.png" class="img-fluid"></a>
                <button class="navbar-toggler" type="button" data-toggle="offcanvas">
                    <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
                </button>
                <div class="navbar-collapse offcanvas-collapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="blog.php">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="faqs.php">Faq</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payment-status.php">Payment Status</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-light rounded-pill" href="signin.php">Sign in</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main role="main" class="pt-5">
        <section id="signup-container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-6">
                        
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-container">
                                            <form class="st-form" id="reset_pwd_form" method="post">
                                                <h2>Reset Password</h2>
                                                <hr>
                                                <div class="form-group">
                                                    <input type="password" id="new_pwd" name="new_pwd" class="form-control" placeholder="Password" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="pwd_error"> Error! Password can not be empty 
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" id="pwd_confirm" name="pwd_confirm" class="form-control" placeholder="Confirm Password" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="pwd_confirm_error"> Error! Password confirm can not be empty
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="pwd_match_error"> Error! Password and confirm password do not match
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="token" name="token" value="<?php echo $token;?>"/>
                                                    <button id="btn_submit" type="button" class="btn btn-danger btn-lg btn-block" style="background-color:white;">Reset</button>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
            </div>
        </section>
                <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-4 col-lg-4 mb-lg-5">
                        <p><img src="img/st-logo.png" class="img-fluid"></p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua.</p>
                        <ul class="list-inline">
                            <li class="list-inline-item link-icon"><a href="#"><i class="fab fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-2 mb-lg-5 mb-4">
                        <h4>Useful Links</h4>
                        <ul class="nav nav-link">
                            <li><a href="#">About</a></li>
                            <li><a href="blog.php">Blog</a></li>
                            <li><a href="#">Enterprise</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="#">Terms of use</a></li>
                            <li><a href="#">Privacy policy</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 mb-lg-5 mb-4">
                        <h4>Contact Us</h4>
                        <p><span class="mr-2"><i class="fas fa-envelope"></i></span> support@streetzwyze.com</p>
                        <p>Murphies Plaza, Sanusi Fafunwa Street, Victoria Island, 100211, Lagos</p>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 mb-lg-5 mb-4">
                        <h4>Download App</h4>
                        <ul class="list-inline">
                            <li class="list-inline-item link-icon"><a href="#"><i class="fab fa-android"></i></a></li>
                            <li class="list-inline-item link-icon"><a href="#"><i class="fab fa-apple"></i></a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-12">
                        <p class="copyright">Copyright © Safeonline. All rights reserved. 2020</p>
                    </div>
                </div>
            </div>
        </footer>    
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

<script type="text/javascript">
    $(function() {
        var lis = $(".sectionHero-upsell-list > li"),
            currentHighlight = 0;
            N = 5;//interval in seconds
        setInterval(function() {
            currentHighlight = (currentHighlight + 1) % lis.length;
            lis.removeClass('is-active').eq(currentHighlight).addClass('is-active');
        }, N * 1000);

        $('.error_Msg').hide();
    });

    $("#btn_submit").click(function(event){
        var form = $("form#reset_pwd_form");
        var new_pwd = $("#new_pwd").val();
        var pwd_confirm = $("#pwd_confirm").val();
        if (new_pwd != "")
        {
            $('#pwd_error').hide();
        }
        else
        {
            $('#pwd_error').show();
            event.preventDefault();
            return;
        }
        if (pwd_confirm != "")
        {
            $('#pwd_confirm_error').hide();
        }
        else
        {
            $('#pwd_confirm_error').show();
            event.preventDefault();
            return;
        }

        if (new_pwd == pwd_confirm)
        {
            $('#pwd_match_error').hide();
        }
        else
        {
            $('#pwd_match_error').show();
            event.preventDefault();
            return;
        }
        form.attr("action", "admin-seller/reset_pwd.php");
        form.submit();
    });
</script>