<?php
include 'config.php';

error_reporting(0);
session_start();

// Cek apakah sudah login
if (isset($_SESSION['email'])) {
    header("Location: reservasi.html");
}

// Cek apakah email telah dikirim
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah email dan password sesuai
    $sql = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        // Simpan Session
        $_SESSION['username'] = $row['username'];
        // Redirefct ke reservasi.html
        header("Location: reservasi.html");
    } else {
        // Alert jika username dan password tidak sesuai
        echo "<script>alert('Email atau password Anda salah. Silahkan coba lagi!')</script>";
    }
}

