<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="style_register.css">

    <title>Register Form</title>
</head>
<body>
    <div class="container">
        <!-- Action ke validation ygy buat login -->
        <form action="#" method="POST" class="login-email">
            <p class="register-text" style="font-size: 2rem; font-weight: 800;">Register</p>
            <div class="input-group">
                <input type="text" placeholder="Username" name="username" value="" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <input type="text" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <button name="kirim" class="btn">Register</button>
            </div>
            <p class="login-register-text">Already have an account? <a href="login_form.php">Back To Login</a></p>
            <br>

        </form>
    </div>
</body>
</html>
<?php
//bikin login form --> (login, register, forgot)
// main form --> simple html

require "connection.php";

if (isset($_POST['kirim'])) {
    if (!empty($_POST["username"]) and !empty($_POST["password"])) {

        // mencegah sql injection dengan bbrp function bawaan php
        // Data2 diambil dari inputan user di form
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        // Query ambil semua data di dalam db buat nanti di cek ada yg kena affected ga row nya
        $result = mysqli_query($connection, "SELECT * FROM login where username = '$username' and password = '$password'");

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
            } else {
                echo "<script type='text/javascript'> alert('Failed to create account'); document.location.href='register.php';</script>";
            }

        } else {
            echo "<script type='text/javascript'> alert('That username/password already exists! Please try again with another.'); document.location.href='register.php';</script>";
            exit();
        }

    }
}
?>