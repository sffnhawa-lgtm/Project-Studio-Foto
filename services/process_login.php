<?php
    session_start();
    include("../connection.php");

    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $is_remebered = isset($_POST["remember"]) ?? FALSE;

    $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
        $_SESSION["admin"] = TRUE;

        if ($is_remebered) {
            setcookie("remember_admin", "true", time() + (86400 * 1), "/"); 
        }

        header("Location: ../index.php?page=admin");
        exit;
    } else {
        header("Location: ../index.php?page=admin&error=1");
        exit;
    }
?>