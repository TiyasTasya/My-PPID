<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['admin_user']) || empty($_SESSION['admin_user'])) {

    header("Location: ../login.php");
    exit;
}
?>