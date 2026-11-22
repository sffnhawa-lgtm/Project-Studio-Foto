<?php
    include("../connection.php");
    include("../includes/helpers.php");

    $username         = trim($_POST["username"] ?? '');
    $password         = $_POST["password"] ?? '';
    $password_confirm = $_POST["password_confirm"] ?? '';

    if ($username === '' || $password === '') {
        redirect("../index.php?page=register&error=kosong");
    }
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        redirect("../index.php?page=register&error=format");
    }
    if (strlen($password) < 6) {
        redirect("../index.php?page=register&error=pendek");
    }
    if ($password !== $password_confirm) {
        redirect("../index.php?page=register&error=beda");
    }

    $cek = $connect->prepare("SELECT username FROM users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    if ($cek->get_result()->fetch_assoc()) {
        redirect("../index.php?page=register&error=ada");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $connect->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);
    $stmt->execute();

    redirect("../index.php?page=admin&registered=1");
?>
