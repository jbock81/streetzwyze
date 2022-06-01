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
    <title>Streetzwyze | Penalty Fees</title>
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
                            <div class="col-lg-6 offset-lg-3 my-3">
                                <h2>Penalty Fee</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-6 offset-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="st-form penalty-invoice">
                                            <div class="table-responsive">
                                                <table class="table table-striped" style="width:100%">
                                                    <tbody>
                                                        <tr>
                                                            <th></th>
                                                            
                                                            <td class="text-right"><small><strong>Order Tag</strong></small><br><p id="order_tag"><!--029295205--></p></td>
                                                        </tr>
                                                        <tr>
                                                            <th><h3 class="badge badge-danger">FEE</h3></th>
                                                            
                                                            <td class="text-right"><small><strong>Amount</strong></small><br>N <p id="penalty_fee"><!--20,000--></p></td>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            
                                                            <td class="text-right"><small><strong>Date</strong></small><br><p id="due_date"><!--2009/09/15 18:00--></p></td>
                                                        </tr>
                                                        <tr>
                                                            <td><small><strong>Due Now</strong></small></td>
                                                            
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>        
                                            <a href="#" class="btn btn-danger btn-lg btn-block mt-2">SETTLE</a>
                                        </div>    
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

<script type="text/javascript">
    function get_penalty_status()
    {
        if (eval(penalty_status) == 1)
        {
            $.post(
                "admin-seller/ajax/getpenaltyfee.php",
                {
                    orderAmount: order_amount,
                    reservationID: reservation_id,
                    dueDate: due_date,
                    MId: m_id
                },
                function(data){
                    var obj_data = JSON.parse(data);
                    $("#order_tag").html(obj_data.orderTag);
                    $("#penalty_fee").html(obj_data.penaltyFee);
                    $("#due_date").html(obj_data.dueDate);
                    
                }
            );
        }
    }
    $(document).ready(function(){
       setTimeout("get_penalty_status()", 3000);
    });
</script>