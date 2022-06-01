<?php
    include ("../include/dbconnect.php");

    $target_dir = "../admin-seller/profile-pics/";
    $target_file = $target_dir . basename($_FILES["photo_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_file = $target_dir . base64_encode(basename($_FILES["photo_file"]["name"])) . "." . $imageFileType;
    $photo_filename = base64_encode(basename($_FILES["photo_file"]["name"])) . "." . $imageFileType;
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photo_file"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    
    // Check if file already exists
    if (file_exists($target_file)) {
        unlink($target_file);
        echo "Sorry, file already exists.";
        $uploadOk = 1;
    }

    // Check file size
    if ($_FILES["photo_file"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        
        if (move_uploaded_file($_FILES["photo_file"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["photo_file"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    if (isset($_POST['biz_name']) && !empty($_POST['biz_name']))
        $biz_name = mysqli_real_escape_string($conn, $_POST['biz_name']);

    if (isset($_POST['email']) && !empty($_POST['email']))
        $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (isset($_POST['phone']) && !empty($_POST['phone']))
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    if (isset($_POST['account_num']) && !empty($_POST['account_num']))
        $account_num = mysqli_real_escape_string($conn, $_POST['account_num']);

    if (isset($_POST['bank_code']) && !empty($_POST['bank_code']))
        $cbn_code = mysqli_real_escape_string($conn, $_POST['bank_code']);

    $payment_tag = $account_num . $cbn_code;

    $sql = "update merchant set Emailaddress='" . $email . "', Mobilenumber='" . $phone . "', Photo='" . $photo_filename . "', Businessname='" . $biz_name . "', PaymentTag='" . $payment_tag . "' where MId='" . $_SESSION["MId"] . "'";
    $result = mysqli_query($conn,$sql);
    echo "<script>location.href='../reservation.php';</script>";
?>