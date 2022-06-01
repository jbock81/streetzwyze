<?php
    include ("../include/dbconnect.php");

    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    $sql = "select * from merchant where Token='" . $token . "'";

    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0)
    {
        if (isset($_POST['new_pwd']) && !empty($_POST['new_pwd']))
            $new_pwd = mysqli_real_escape_string($conn, $_POST['new_pwd']);
        $sql = "update merchant set Mpassword='" . md5($new_pwd) . "' where Token='" . $token . "'";
        $result = mysqli_query($conn,$sql);
        header("Location: ../dashboard.php");
    }
    else
        header("Location: ../index.php");

?>