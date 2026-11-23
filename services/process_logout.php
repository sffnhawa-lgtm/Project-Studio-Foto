<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    if (!empty($_SESSION["username"])) {
        $stmt = $connect->prepare("UPDATE users SET remember_token = NULL WHERE username = ?");
        $stmt->bind_param("s", $_SESSION["username"]);
        $stmt->execute();
    }

    session_unset();
    session_destroy();

    setcookie("remember_admin", "", time() - 3600, "/");

    redirect("../index.php?page=login");
?>
