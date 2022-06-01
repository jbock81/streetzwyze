<?php
    if (isset($_SESSION))
        session_destroy();

    header("Location: ../signin.php");
?>