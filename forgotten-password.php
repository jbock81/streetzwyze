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
                            <h2>Password Reset</h2>
                            <form method="post" id="forgotten_pwd_form">
                                <p>To reset your password enter the email address you used to sign in to your dashboard.</p>
                                <div class="form-group pt-4">
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                      </div>
                                      <input type="text" id="seller_email" name="seller_email" class="form-control" placeholder="Seller Email" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p class="error_Msg" id="email_error"> Error! Email should only look like xyz@abc.com 
                                    </p>
                                </div>
                                <div class="form-group">
                                    <p class="error_Msg" id="email_error2"> Error! This email is not exists. Please check again.
                                    </p>
                                </div>
                                <div class="form-group">
                                    <button id="btn_submit" type="button" class="btn btn-warning btn-lg btn-block">Reset</button>
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
        var form = $("form#forgotten_pwd_form");
        var seller_email = $("#seller_email").val();
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
        $.post(
            "admin-seller/ajax/checkcuruser.php",
            {
                seller_email: seller_email
            },
            function(data){
                if (data != "no_exist")
                {
                    form.attr("action", "admin-seller/forgotpass.php");
                    form.submit();
                }
                else
                {
                    $("#email_error2").show();
                    event.preventDefault();
                    return;
                }
            }
        );
        // form.attr("action", "admin-seller/forgotten_pwd.php");
        // form.submit();
    });
</script>