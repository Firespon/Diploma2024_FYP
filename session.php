<?php
    session_start();

    if (!isset($_SESSION["mySession"])){
        echo "<script>alert('Please login!');window.location.href='php/Register.php';</script>";
    }
?>