<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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
if (isset($_POST["kirim_forgot"]) and !empty($_POST["email"])) {
    $email = $_POST["email"];
    $sql = "SELECT * FROM login where email = '$email'";
    $result = mysqli_query($connection, $sql);
    $num_row = mysqli_num_rows($result);

    if ($num_row > 0) {

        // Generate Random token where the token will send to the email
        //Generate a random string.
        $token = openssl_random_pseudo_bytes(4);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fabianjuliansyah89@gmail.com'; // Gmail kamu
        $mail->Password = 'kssqdwepoedjaprx'; // Kode key dari google accunt App Password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('fabianjuliansyah89@gmail.com'); // Gmail kamu
        $mail->addAddress($_POST['email']); // Email tujuan
        $mail->isHTML(true);
        $mail->Subject = "Your Recovery Token Password";
        $mail->Body = 'Copy this token to otp-page : ' . $token;

        $mail->send();
        echo "<script type='text/javascript'> alert('Code has been sent to your email address!'); document.location.href='otp-page.php';</script>";
    } else {
        $errors['email'] = "Sorry your email is not registered";
    }
}

// Validasi Tombol OTP
if (isset($_POST["kirim_otp"])) {
    if (!empty($_POST["otp"])) {
        // Ambil password adri inputan user
        $password = $_POST["otp"];
        // Cek password user
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);

        if (strlen($password) < 6 || !$number || !$uppercase || !$lowercase) {
            $errors['pass_strength'] = "Sorry your password is not match";
        } else {

        }
    }
}
