<?php
    include_once ("./include/dbconnect.php");

    $bank_list = array(array());
    $k = 0;

    $sql = "select * from bank_cbn";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $bank_list[$k]['bid'] = $row['bid'];
            $bank_list[$k]['qt_code'] = $row['qt_code'];
            $bank_list[$k]['bank'] = $row['bank'];
            $bank_list[$k]['cbn_code'] = $row['cbn_code'];
            $k++;        
        }
    }

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
    <title>Streetzwyze | Disbursement</title>
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
                            <div class="col-lg-8 offset-lg-2 my-3">
                                <h2>Resend Payout</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-8 offset-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="st-form" id="payment_form" name="payment_form" method="post">
                                            <div class="form-group">
                                                <input type="text" id="account_num" name="account_num" class="form-control" placeholder="Account Number" />
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="account_num_error"> Error! Account number can contain only numbers from 0-9 
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <select required="required" class="form-control" id="bank_code" name="bank_code" data-name="bank_code" data-help="Select destination bank from dropdown for disbursement of payment to seller.<br><br>" data-validate="required" data-validateerror="Please select bank.<br><br>">
                                                    <option value="">Select bank</option>
                                                    <?php
                                                        foreach ($bank_list as $item)
                                                        {
                                                            echo '<option value="' . $item["cbn_code"] . '">' . $item["bank"] . '</option>';
                                                        }
                                                    ?>
                                                    <!--
                                                    <option value="">Select bank</option>
                                                    <option value="044">Access Bank</option>
                                                    <option value="050">Ecobank</option>
                                                    <option value="070">Fidelity Bank</option>
                                                    <option value="011">First Bank</option>
                                                    <option value="214">First City Monument Bank</option>
                                                    <option value="058">Guaranty Trust Bank</option>
                                                    <option value="030">Heritage Bank</option>
                                                    <option value="301">Jaiz Bank</option>
                                                    <option value="082">Keystone Bank</option>
                                                    <option value="101">Providus Bank</option>
                                                    <option value="076">Polaris Bank</option>
                                                    <option value="039">Stanbic IBTC</option>
                                                    <option value="232">Sterling Bank</option>
                                                    <option value="100">Suntrust Bank</option>
                                                    <option value="032">Union Bank</option>
                                                    <option value="033">United Bank For Africa</option>
                                                    <option value="215">Unity Bank</option>
                                                    <option value="035">Wema Bank</option>
                                                    <option value="057">Zenith Bank</option>
                                                    -->
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="cbn_code_error"> Error! You should select a bank from the bank list 
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" id="OId" name="OId" value="<?php echo $_GET["OId"];?>"/>
                                                <button class="btn btn-block btn-danger" id="payment_submit" type="button">Submit</button>
                                            </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        $(".error_Msg").hide();
        $("#payment_submit").click(function(e){
            var form = $("form#payment_form");
            var account_num = $("#account_num").val();
            var cbn_code = $("#bank_code").val();
            var o_id = $("#OId").val();

            var regex_account_num = /^[0-9]+$/;
            if (regex_account_num.test(account_num))
            {
                $('#account_num_error').hide();
            }
            else
            {
                $('#account_num_error').show();
                e.preventDefault();
                return;
            }

            if (cbn_code != "")
            {
                $('#cbn_code_error').hide();
            }
            else
            {
                $('#cbn_code_error').show();
                e.preventDefault();
                return;
            }
            //form.attr("action", "admin-seller/retry_payment.php");
            //form.submit();
            $.post(
                "admin-seller/ajax/retry_payment.php",
                {
                    account_num: account_num,
                    bank_code: cbn_code,
                    OId: o_id
                },
                function(data)
                {
                    $("#spval3").text(data);
                    //location.href = "disbursements.php";
                }
            );
        });
    });
</script>