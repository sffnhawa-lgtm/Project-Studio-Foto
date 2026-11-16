<?php
    $host_name = "localhost";
    $username = "root";
    $password = "";
    $database = "db_studio_foto";

    $connect = new mysqli(
        $host_name,
        $username, $password,
        $database
    );

    if ($connect->connect_error){
        die("Koneksi gagal: ". $connect->connect_error);
    }
?>