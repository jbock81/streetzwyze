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
    <title>Streetzwyze | Disbursements</title>
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
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                            <div class="col-lg-8 offset-lg-2 my-3">
                                <h2>Disbursements</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-8 offset-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group mb-3">
                                                    <input type="text" id="datepicker_from" class="form-control datepicker" placeholder="From">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group mb-3">
                                                    <input type="text" id="datepicker_to" class="form-control datepicker" placeholder="To">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="disbursements_tbl" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Order Tag</th>
                                                        <th class="d-none d-lg-table-cell">Amount</th>
                                                        <th>Status</th>
                                                        <th class="d-none d-lg-table-cell">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="disbursements_body">
                                                    <!--
                                                    <tr>
                                                        <td>Colleen Hurst</td>
                                                        <td class="d-none d-lg-table-cell">Javascript Developer</td>
                                                        <td><span class="badge badge-warning text-white">Processing</span></td>
                                                        <td class="d-none d-lg-table-cell">2009/09/15</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sonya Frost</td>
                                                        <td class="d-none d-lg-table-cell">Software Engineer</td>
                                                        <td><span class="badge badge-success">Success</span></td>
                                                        <td class="d-none d-lg-table-cell">2008/12/13</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jena Gaines</td>
                                                        <td class="d-none d-lg-table-cell">Office Manager</td>
                                                        <td>
                                                            <span class="badge badge-danger">Failed</span> 
                                                            <a href="retry-payment.php" class="text-success"><i class="fas fa-redo"></i></a>
                                                        </td>
                                                        <td class="d-none d-lg-table-cell">2008/12/19</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Quinn Flynn</td>
                                                        <td class="d-none d-lg-table-cell">Support Lead</td>
                                                        <td>33</td>
                                                        <td class="d-none d-lg-table-cell">2013/03/03</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Charde Marshall</td>
                                                        <td class="d-none d-lg-table-cell">Regional Director</td>
                                                        <td>36</td>
                                                        <td class="d-none d-lg-table-cell">2008/10/16</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Haley Kennedy</td>
                                                        <td class="d-none d-lg-table-cell">Senior Marketing</td>
                                                        <td>43</td>
                                                        <td class="d-none d-lg-table-cell">2012/12/18</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tatyana Fitzpatrick</td>
                                                        <td class="d-none d-lg-table-cell">Regional Director</td>
                                                        <td>19</td>
                                                        <td class="d-none d-lg-table-cell">2010/03/17</td>
                                                    </tr>
                                                    -->
                                                </tbody>
                                            </table>
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
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="js/jquery.mmenu.all.js"></script>
    <script src="js/dashboard.js"></script>
    
</body>

</html>

<script type="text/javascript">

    function get_disbursements(ddate_from, ddate_to)
    {
        $.post(
            "admin-seller/ajax/getdisbursements.php",
            {
                dd_from: ddate_from,
                dd_to: ddate_to
            },
            function(data){
                if (data != "[[]]")
                {
                    var obj_data = JSON.parse(data);
                    var str_body = "";
                    for (var i=0; i<obj_data.length; i++)
                    {
                        str_body += '<tr><td>' + obj_data[i]["OrderTag"] + '</td>';
                        str_body += '<td class="d-none d-lg-table-cell">' + obj_data[i]["Amount"] + '</td>';
                        str_body += '<td><span class="badge badge-warning text-white">' + obj_data[i]["Status"];
                        if (obj_data[i]["Status"] == "Failed")
                            str_body += '<a href="retry-payment.php?OId=' + obj_data[i]["OId"] + '" class="text-success"><i class="fas fa-redo"></i></a>';
                        str_body += '</td>';
                        str_body += '<td class="d-none d-lg-table-cell">' + obj_data[i]["Reservedate"] + '</td></tr>';
                    }
                }
                $("#disbursements_body").html(str_body);
            }
        );
        $('#disbursements_tbl').DataTable();
    }

    $(document).ready(function() {
        var ddate = new Date();
        var ddate_from = "";
        var ddate_to = "";

        if (ddate_from == "")
        {
            if (ddate.getMonth() < 9)
                ddate_from = "0" + (ddate.getMonth() + 1);
            else
                ddate_from += (ddate.getMonth() + 1);
            
            if (ddate.getDate() < 10)
                ddate_from += "/0" + (ddate.getDate());
            else
                ddate_from += "/" + ddate.getDate();

            ddate_from += "/" + (ddate.getYear() + 1900);
        }

        if (ddate_to == "")
        {
            if (ddate.getMonth() < 9)
                ddate_to = "0" + (ddate.getMonth() + 1);
            else
                ddate_to += (ddate.getMonth() + 1);
            
            if (ddate.getDate() < 10)
                ddate_to += "/0" + (ddate.getDate());
            else
                ddate_to += "/" + ddate.getDate();
            ddate_to += "/" + (ddate.getYear() + 1900);
        }

        //alert(ddate_from + " : " + ddate_to);
        $("#datepicker_from").val(ddate_from);
        $("#datepicker_to").val(ddate_to);
        $(".datepicker").datepicker({
            orientation: "bottom"
        });

        get_disbursements(ddate_from, ddate_to);

        $("#datepicker_from").on("change", function(){
            ddate_from = $(this).val();
            ddate_to = $("#datepicker_to").val();
            get_disbursements(ddate_from, ddate_to);
        });

        $("#datepicker_to").on("change", function(){
            ddate_from = $("#datepicker_from").val();
            ddate_to = $(this).val();
            get_disbursements(ddate_from, ddate_to);
        });
        
    });
</script>