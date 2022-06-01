<?php
    include_once ("../include/dbconnect.php");

    if (isset($_POST['old_pwd']) && !empty($_POST['old_pwd']))
        $old_pwd = mysqli_real_escape_string($conn, $_POST['old_pwd']);
    
    if (isset($_POST['new_pwd']) && !empty($_POST['new_pwd']))
        $new_pwd = mysqli_real_escape_string($conn, $_POST['new_pwd']);

    $sql = "select * from merchant where MId = '" . $_SESSION['MId'] . "' and Mpassword='" . md5($old_pwd) . "'";
    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $sql = "update merchant set Mpassword='" . md5($new_pwd) . "' where MId = '" . $_SESSION['MId'] . "'";
            $result = mysqli_query($conn,$sql);
            header("Location: ../change-password.php?pwd_chg_success=1");
        }
    }
    else
    {
        echo "<script>location.href='../change-password.php';</script>";   
    }
?>