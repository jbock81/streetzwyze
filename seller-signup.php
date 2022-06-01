<?php
    if (isset($_GET['mid']) && !empty($_GET['mid']))
        $referral_id = intval($_GET['mid']);
    else
        $referral_id = 0;
    
    if (isset($_GET['reg_success']) && !empty($_GET['reg_success']))
        $reg_success = intval($_GET['reg_success']);
    else
        $reg_success = 0;
    
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
                    <div class="col-lg-12">
                        <div id="signup-slider" class="owl-carousel mt-4">
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="text-content pb-0">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat.</p>
                                        </div>
                                        <div class="sectionHero-upsell">
                                            <div class="sectionHero-steps">
                                                <span class="sectionHero-steps-decorator"></span>
                                                <ol class="sectionHero-upsell-list">
                                                    <li class="sectionHero-upsell-item">
                                                        <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path>
                                                        </svg>
                                                        <span class="sectionHero-upsell-desc">
                                                            Retrieve active reservation
                                                        </span>
                                                    </li>
                                                    <li class="sectionHero-upsell-item is-active">
                                                        <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path>
                                                        </svg>
                                                        <span class="sectionHero-upsell-desc">
                                                            Buyer inspects good or service
                                                        </span>
                                                    </li>
                                                    <li class="sectionHero-upsell-item">
                                                        <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path>
                                                        </svg>
                                                        <span class="sectionHero-upsell-desc">
                                                            Buyer approves
                                                        </span>
                                                    </li>
                                                    <li class="sectionHero-upsell-item">
                                                        <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path>
                                                        </svg>
                                                        <span class="sectionHero-upsell-desc">
                                                            Request for code
                                                        </span>
                                                    </li>
                                                    <li class="sectionHero-upsell-item">
                                                        <svg class="sectionHero-upsell-icon" width="19" height="16" viewBox="0 0 19 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.69 6.892l2.322 2.21L16.35.312c.38-.4 1.012-.418 1.413-.038.4.38.417 1.013.037 1.414l-9.027 9.517c-.38.402-1.014.418-1.415.037L4.31 8.34c-.4-.38-.415-1.014-.034-1.414.38-.4 1.014-.415 1.414-.034zM11.836.978c.484.265.662.873.397 1.357-.265.485-.873.663-1.357.398C10.002 2.253 9.02 2 8 2 4.686 2 2 4.686 2 8s2.686 6 6 6 6-2.686 6-6c0-.552.448-1 1-1s1 .448 1 1c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8c1.358 0 2.67.34 3.836.978z" fill-rule="nonzero"></path>
                                                        </svg>
                                                        <span class="sectionHero-upsell-desc">
                                                            Confirm account deposit
                                                        </span>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm signup-seller d-block d-lg-none">Sign up</a>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-container">
                                            <form class="st-form" id="signup_form" method="post">
                                                <h2>Sign Up As A Seller</h2>
                                                <hr>
                                                <div class="form-group">
                                                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="fname_error"> Error! First name can not be empty
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="lname_error"> Error! Last name can not be empty
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" id="seller_email" name="seller_email" class="form-control" placeholder="Seller Email" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="email_error"> Error! Email should only look like xyz@abc.com 
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" id="seller_phone" name="seller_phone" class="form-control" placeholder="Seller Phone Number" required/>
                                                </div>
                                                <div class="form-group">
                                                    <p class="error_Msg" id="phone_error"> Error! Phone number can contain only numbers from 0-9 and + or - sign 
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="password" id="seller_pwd" name="seller_pwd" class="form-control" placeholder="Password" required/>
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
                                                    <p class="error_Msg" id="user_exist"> Error! The user with same email or phone number already exist
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="tanda">
                                                        <label class="form-check-label" for="tanda">
                                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmo tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" id="referral_id" name="referral_id" value="<?php echo $referral_id;?>"/>
                                                    <button id="btn_submit" type="button" class="btn btn-danger btn-lg btn-block">Submit</button>
                                                </div>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/nivo-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/app.js"></script>
    
</body>

</html>

<script type="text/javascript">
    var reg_success = '<?php echo $reg_success; ?>';
    $(function() {
        var lis = $(".sectionHero-upsell-list > li"),
            currentHighlight = 0;
            N = 5;//interval in seconds
        setInterval(function() {
            currentHighlight = (currentHighlight + 1) % lis.length;
            lis.removeClass('is-active').eq(currentHighlight).addClass('is-active');
        }, N * 1000);

        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': true,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
        
        $('.error_Msg').hide();

        if (eval(reg_success) == 1)
            toastr.success('Information received successfully, check your inbox for verification mail');
    });

    $("#btn_submit").click(function(event){
        var form = $("form#signup_form");
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var seller_email = $("#seller_email").val();
        var seller_phone = $("#seller_phone").val();
        var seller_pwd = $("#seller_pwd").val();
        var pwd_confirm = $("#pwd_confirm").val();

        if (fname != "")
        {
            $('#fname_error').hide();
        }
        else
        {
            $('#fname_error').show();
            event.preventDefault();
            return;
        }

        if (lname != "")
        {
            $('#lname_error').hide();
        }
        else
        {
            $('#lname_error').show();
            event.preventDefault();
            return;
        }

        var regex_email = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (regex_email.test(seller_email))
        {
            $('#email_error').hide();
        }
        else
        {
            $('#email_error').show();
            event.preventDefault();
            return; 
        }
        
        var regex_phone = /^[0-9-+]+$/;
        if (regex_phone.test(seller_phone))
        {
            $('#phone_error').hide();
        }
        else
        {
            $('#phone_error').show();
            event.preventDefault();
            return;
        }

        if (seller_pwd != "")
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

        if (seller_pwd == pwd_confirm)
        {
            $('#pwd_match_error').hide();
        }
        else
        {
            $('#pwd_match_error').show();
            event.preventDefault();
            return;
        }

        $.post(
            "admin-seller/ajax/checkuser.php",
            {
                seller_email: seller_email,
                seller_phone: seller_phone
            },
            function(data){
                if (data == "no_exist")
                {
                    form.attr("action", "admin-seller/register.php");
                    form.submit();
                }
                else
                {
                    $("#user_exist").show();
                    event.preventDefault();
                    return;
                }
            }
        );
        
    });
</script>