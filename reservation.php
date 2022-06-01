<?php
    include_once ("./include/dbconnect.php");
    $str_fullname = $_SESSION['Firstname'];

    if (isset($_SESSION['Photo']))
        $photo = $_SESSION['Photo'];
    else
        $photo = "//placehold.it/50x50";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" />
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
    <?php 
        include "dashboard_header.php";
    ?>
    <main>
        <?php
            include "dashboard_profile.php";
        ?>
        <div class="container-fluid mx-0">
            <div class="jumbotron m-0 px-0">
                <div class="row">
                    <div class="col-lg-10">
                        <?php
                            include "dashboard_tiles.php";
                        ?>
                        <div class="row mt-lg-5">
                            <div class="col-lg-6 offset-lg-3">
                                <h2>Reservation</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-6 offset-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="st-form" id="reserve_form1" method="post">
                                            <div class="form-group mb-3">
                                                <input type="text" id="reservation_date" name="reservation_date" class="form-control datepicker">
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                </div>
                                                <input type="text" id="delivery_duration" name="delivery_duration" class="form-control" placeholder="Select Delivery Date">
                                            </div>
                                            <div class="form-group mb-3">
                                                <p class="error_Msg" id="ddate_error"> Error! Delivery date can not be empty
                                                </p>
                                            </div>
                                            <p class="text-right">
                                                <button type="button" class="btn btn-danger" id="btn_next">Next</button>
                                            </p>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="js/jquery.mmenu.all.js"></script>
    <script src="js/dashboard.js"></script>

</body>

</html>

<script language="javascript">

    function diff_hours(dt2, dt1) 
    {
        var diff =(dt2.getTime() - dt1.getTime()) / 1000;
        diff /= (60 * 60);
        return Math.abs(Math.round(diff));
    }

    $(document).ready(function(){
        var timeDiff = 0;

        $(".datepicker").datetimepicker({
            //format: 'd.m.Y H:i',
            format: 'Y-m-d',
            inline: true,
            lang: 'en',
            timepicker: false,
            yearStart: '2020',
            yearEnd: '2022',
            scrollMonth: false,
            onSelectDate: function(ct,$i){
                var dd = new Date();
                var value_start = new Date(dd.getYear() + 1900, dd.getMonth() + 1, dd.getDate());
                var arr_date = $("#reservation_date").val().split("-");
                var value_top = new Date(arr_date[0],arr_date[1],arr_date[2]);
                timeDiff = diff_hours(value_top, value_start);
                if (timeDiff > 0)
                    timeDiff = timeDiff - 6;
                $("#delivery_duration").val(timeDiff);
                $(".error_Msg").hide();
            }
        });

        $(".error_Msg").hide();
        $("#btn_next").click(function(e){

            var form = $("form#reserve_form1");
            var delivery_duration = $("#delivery_duration").val();
            var dd_reg = /^[0-9]{1,3}$/;
            if (!dd_reg.test(delivery_duration))
            {
                $("#ddate_error").show();
                e.preventDefault();
                return;
            }
            else
            {
                form.attr("action", "reservation-step-2.php");
                form.submit();
            }
        });
    });
</script>