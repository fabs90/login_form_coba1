<?php
// Sambungkan dengan koneksi db di halaman connection
require "connection.php";

// Jika apakah tombol button kirim sudah di klik
if (isset($_POST["kirim"])) {

    // Input data masukan user ke variabel
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Jika form username dan password TIDAK kosong
    if (!empty($_POST["username"]) and !empty($_POST["password"])) {

        // Anti sql injection
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);

        // Query SQL, karena password di hash, maka cari kondisi nya pake username aja
        $query = "SELECT password FROM login where username ='$username'";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);

        // Jika password yg diinput user sama dengan password hash di db
        // Password_verify() dipake buat validasi password_hash
        if (password_verify($password, $row['password'])) {
            // Start Sesi
            session_start();
            // Declare variabel sesi username
            $_SESSION['username'] = $username;
            // Pindah Halaman ke home
            header("Location:home.php");
        } else {
            echo "<script type='text/javascript'> alert('Wrong Username/Password!'); document.location.href='login_form.php';</script>";
        }

    } else {
        echo "<script type='text/javascript'> alert('All fields are required!'); document.location.href='login_form.php';</script>";
    }
}
