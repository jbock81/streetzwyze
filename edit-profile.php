<?php
    include_once ("./include/dbconnect.php");

    $str_fullname = $_SESSION['Firstname'];
    if (isset($_SESSION['Photo']))
        $photo = $_SESSION['Photo'];
    else
        $photo = "//placehold.it/50x50";
    $bank_list = array(array());
    $k = 0;

    $sql = "select * from bank_cbn order by bank asc";
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

    
    $sql = "select * from merchant where MId ='" . $_SESSION["MId"] . "'";
    $result = mysqli_query($conn, $sql);

    $biz_name = "";
    $fname = "";
    $lname = "";
    $email = "";
    $phone = "";
    $account_num = "";
    $cbn_code = "";
    $photo_filename = "//placehold.it/190x190";
    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $biz_name = $row["Businessname"];
            $fname = $row["Firstname"];
            $lname = $row["Lastname"];
            $email = $row["Emailaddress"];
            $phone = $row["Mobilenumber"];
            $account_num = ltrim(substr($row['PaymentTag'], 0, 10));
            $cbn_code = rtrim(substr($row['PaymentTag'], 10));
            if ($row['Photo'] != "")
                $photo_filename = "/admin-seller/profile-pics/" . $row['Photo'];
        }
    }

    $dflag = 0;
    if ($biz_name != "" && $account_num != "" && $cbn_code != "")
        $dflag = 1;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Enjoy! purchases across social media, classifieds or retail shops with control to determine when and how you receive your good or service.">
    <meta name="author" content="Monster Studios">
    <meta name="keywords" content="Streetzwyze, secure payment, buyer proctection, seller proctection, secure exchange">
    <title>Streetzwyze | Sellers Dashboard</title>
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
                                <h2>Edit Profile</h2>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-6 offset-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                    <form id="profile_form" name="profile_form" method="post" enctype="multipart/form-data">
                                        <div class="profile-badge mt-3">
                                            <label for="photo_file">
                                            <span id="profile-main">
                                                <img id="img_photo" src="<?php echo $photo_filename;?>" class="rounded-circle mx-auto" width="190px" height="190px">
                                                <input type="file" name="photo_file" id="photo_file" style="display:none;" accept="image/*"/>
                                            </span>
                                            </label>
                                            <h3 class="pt-4 text-danger">Profile Strength</h3>
                                        </div>    
                                        <div class="st-form">
                                            <div class="form-group">
                                                <input type="text" disabled class="form-control" id="biz_name" name="biz_name" value="<?php echo $biz_name;?>" placeholder="Business Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" disabled class="form-control" id="fname" name="fname" value="<?php echo $fname;?>" placeholder="First Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" disabled class="form-control" id="lname" name="lname" value="<?php echo $lname;?>" placeholder="Last Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>" placeholder="Email Address">
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="email_error"> Error! Email should only look like xyz@abc.com 
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone;?>" placeholder="Phone Number">
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="phone_error"> Error! Phone number can contain only numbers from 0-9 and + or - sign 
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="account_num" name="account_num" value="<?php echo $account_num; ?>" placeholder="Account Number" />
                                            </div>
                                            <div class="form-group">
                                                <p class="error_Msg" id="account_num_error"> Error! Account number can contain only 10 digits from 0-9 
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
                                                <p class="error_Msg" id="user_exist"> Error! The user with same email or phone number already exist
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <button id="profile_submit" type="button" class="btn btn-block btn-danger">Submit</button>
                                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
    <script src="js/jquery.mmenu.all.js"></script>
    <script src="js/dashboard.js"></script>
</body>

</html>

<script language="javascript">
    var photo_file = "";
    var dflag = <?php echo $dflag;?>;
    
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img_photo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            photo_file = input.files[0];
        }
    }
    
    $(document).ready(function(){

        if (dflag == 1)
        {
            $("#biz_name").attr("disabled", "disabled");
            $("#account_num").attr("disabled", "disabled");
            $("#bank_code").attr("disabled", "disabled");
        }
        else
        {
            $("#biz_name").removeAttr("disabled");
            $("#account_num").removeAttr("disabled");
            $("#bank_code").removeAttr("disabled");
        }

        $("#photo_file").change(function(){
            readURL(this);
        });

        $('.error_Msg').hide();
        var cbn_code0 = "<?php echo $cbn_code; ?>";
        if (cbn_code0 != "")
        {
            $("#bank_code").val(cbn_code0);
        }
        else
        {
            $("#bank_code").val("");
        }

        if ($("#biz_name").val() == "")
        {
            $("#biz_name").removeAttr("disabled");
        }
        else
        {
            $("#biz_name").attr("disabled", "disabled");
        }

        $("#profile_submit").click(function(e){
            var form = $("form#profile_form");
            var email = $("#email").val();
            var phone = $("#phone").val();
            var account_num = $("#account_num").val();
            var cbn_code = $("#bank_code").val();

            if (photo_file.name == undefined)
            {
                alert("Please select a picture of the user.");
                return;
            }
            
            var regex_email = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (regex_email.test(email))
            {
                $('#email_error').hide();
            }
            else
            {
                $('#email_error').show();
                e.preventDefault();
                return; 
            }

            var regex_phone = /^[0-9]+$/;
            if (regex_phone.test(phone))
            {
                $('#phone_error').hide();
            }
            else
            {
                $('#phone_error').show();
                e.preventDefault();
                return;
            }

            var regex_account_num = /^[0-9]{10}$/;
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

            form.attr("action", "admin-seller/edit_profile.php");
            form.submit();
            /*$.post(
                "admin-seller/ajax/checkuser.php",
                {
                    seller_email: email,
                    seller_phone: phone
                },
                function(data){
                    if (data == "no_exist")
                    {
                        
                    }
                    else
                    {
                        $("#user_exist").show();
                        e.preventDefault();
                        return;
                    }
                }
            );*/
        });

    });
</script>