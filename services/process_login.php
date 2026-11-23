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

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["admin"]    = TRUE;
        $_SESSION["username"] = $user["username"];

        if ($is_remembered) {
            $token = bin2hex(random_bytes(32));
            $hash  = hash('sha256', $token);

            $upd = $connect->prepare("UPDATE users SET remember_token = ? WHERE username = ?");
            $upd->bind_param("ss", $hash, $user["username"]);
            $upd->execute();

            setcookie("remember_admin", $token, [
                "expires"  => time() + 86400,
                "path"     => "/",
                "httponly" => true,
                "samesite" => "Lax",
            ]);
        }

        redirect("../index.php?page=admin");
    }

    redirect("../index.php?page=admin&error=1");
?>