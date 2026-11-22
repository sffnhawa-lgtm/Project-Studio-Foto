<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    $username      = trim($_POST["username"] ?? '');
    $password      = $_POST["password"] ?? '';
    $is_remembered = isset($_POST["remember"]);

    $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
        $_SESSION["admin"]    = TRUE;
        $_SESSION["username"] = $user["username"];

        if ($is_remembered) {
            setcookie("remember_admin", $user["username"], time() + (86400 * 1), "/");
        }

        redirect("../index.php?page=admin");
    }

    redirect("../index.php?page=admin&error=1");
?>