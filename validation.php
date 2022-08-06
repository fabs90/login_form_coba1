<?php
// Sambungkan dengan koneksi db di halaman connection
require "connection.php";
require "function.php";
$errors = array();
$success = array();

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
                $errors["password"] = "Incorrect Username or Password";
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
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        // Query ambil semua data di dalam db buat nanti di cek ada yg kena affected ga row nya
        $result = mysqli_query($connection, "SELECT * FROM login where username = '$username' and email = '$email'");

        // Cek ada berapa row yang sama dari syntax query
        $num_row = mysqli_num_rows($result);

        // Kalo gaada yg sama data nya
        if ($num_row == 0) {

            // Ubah password yg diinput user jadi hash
            $password = mysqli_real_escape_string($connection, password_hash($_POST["password"], PASSWORD_DEFAULT));

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

// (Validasi tombol forgot password)
if (isset($_POST["kirim_forgot"])) {
    // Ambil email dari inputan user
    $email = mysqli_real_escape_string($connection, $_POST["email"]);

    // Query kondisi = email
    $sql = "SELECT * FROM login where email = '$email'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    // Cek apakah data ada
    // Jika tidak ada data maka
    if ($num_row = mysqli_num_rows($result) < 1) {
        $errors["email"] = "Email not found!";
    }

    // Kalau tidak ada error
    if (empty($errors)) {
        // Generate random token (FIX)
        $token_ganti_password = password_hash((rand(0, 1000)), PASSWORD_DEFAULT);
        $judul_email = "Halaman Konfirmasi Forgot Password";
        $isi_email = "Copy the token : " . $token_ganti_password;
        // Function kirim_email
        kirim_email($email, $email, $judul_email, $isi_email);

        // Update token di db menjadi generate token dengan kondisi email yg diinput
        $sql = "UPDATE login SET token_ganti_password = '$token_ganti_password' where email = '$email'";
        mysqli_query($connection, $sql);
        echo "<script type='text/javascript'> alert('Token has been sent to your email!'); document.location.href='change_password.php?';</script>";
    }

}

// (Validasi Tombol Password Baru)
if (isset($_POST["kirim_new_password"])) {
    // Ambil inputan dari user
    $input_token = $_POST["token_confirm"];
    $password = mysqli_real_escape_string($connection, $_POST["new_password"]);
    $confirm_pass = mysqli_real_escape_string($connection, $_POST["password_confirm"]);
    // Kalo textbox input token tidak kosong
    if (!empty($input_token)) {
        // Query sekuruh data berdasarkan token yang udah di ganti
        $sql = "SELECT * FROM login WHERE token_ganti_password = '$input_token'";
        $query = mysqli_query($connection, $sql);
        $num_row = mysqli_num_rows($query);

        // Kalo ada data yg sama sesuai input token
        if ($num_row > 0) {
            // Fetch data dari db
            $row = mysqli_fetch_assoc($query);
            // Variabel token_db berisi token dari db
            $token_db = $row["token_ganti_password"];
            // Bandingin token yg diinput user sama di db
            if ($input_token === $token_db) {
                // Cek apakah password sm dgn confirm passwrd
                if ($password === $confirm_pass) {
                    // Hash password baru
                    $password = mysqli_real_escape_string($connection, password_hash($_POST["new_password"], PASSWORD_DEFAULT));
                    // Query update password
                    $sql = "UPDATE login SET password = '$password' WHERE token_ganti_password = '$input_token'";
                    $query = mysqli_query($connection, $sql);
                    if ($query) {
                        $success["password"] = "Password Succesfully Changes, Back to login please";
                    } else {
                        $errors["password"] = "Failed to update password";
                    }
                }
            }
        } else {
            $errors["db_data"] = "Token is false";
        }
    }
}
