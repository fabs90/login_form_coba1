<?php
// Sambungkan dengan koneksi db di halaman connection
require "connection.php";
$errors = array();

// (Validasi Tombol Login)
// Jika apakah tombol button kirim sudah di klik
if (isset($_POST["kirim_login"])) {

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
        $num_row = mysqli_num_rows($result);

        if ($num_row > 0) {
            // Jika password yg diinput user sama dengan password hash di db
            // Password_verify() dipake buat validasi password_hash
            if (password_verify($password, $row['password'])) {
                // Start Sesi
                session_start();
                // Declare variabel sesi username
                $_SESSION["username"] = $username;
                // Pindah Halaman ke home
                header("Location:home.php");
                exit();
            } else {
                $errors["password"] = "Incorrect Email or Password";
            }
        } else {
            $errors["username"] = "Username not exist! Please sign in first";
        }

    } else {
        $errors["password"] = "Please Fill the required form";
    }
}

// (Validasi Tombol Register)
if (isset($_POST['kirim_regist'])) {
    if (!empty($_POST["username"]) and !empty($_POST["password"])) {

        // mencegah sql injection dengan bbrp function bawaan php
        // Data2 diambil dari inputan user di form
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        // Query ambil semua data di dalam db buat nanti di cek ada yg kena affected ga row nya
        $result = mysqli_query($connection, "SELECT * FROM login where email = '$email'");

        // Cek ada berapa row yang sama dari syntax query
        $num_row = mysqli_num_rows($result);

        // Kalo gaada yg sama data nya
        if ($num_row == 0) {

            // Ubah password yg diinput user jadi hash
            $password = mysqli_real_escape_string($connection, password_hash($password, PASSWORD_DEFAULT));

            // query sql insert data ke table
            $sql = mysqli_query($connection, "INSERT INTO login(username, password, email) VALUES('$username', '$password', '$email')");

            if ($sql) {
                echo "<script type='text/javascript'> alert('Account Successfully created'); document.location.href='register.php';</script>";
                exit();
            } else {
                $errors["db-error"] = "Failed to Insert Data";
            }

        } else {
            $errors["email"] = "Sorry your data have been exist";
        }

    }
}

// (Validasi tombol)
