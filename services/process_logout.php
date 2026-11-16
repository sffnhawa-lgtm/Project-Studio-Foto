<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie("remember_admin", "", time() - 3600, "/");

    header("Location: ../index.php?page=login");
    exit;
?>