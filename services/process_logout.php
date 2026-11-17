<?php
    session_start();
    include("../includes/helpers.php");

    session_unset();
    session_destroy();
    setcookie("remember_admin", "", time() - 3600, "/");

    redirect("../index.php?page=login");
?>
