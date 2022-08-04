<?php
// Sambungkan dengan file validation
// Sehingga variabel2 di form login bisa dipake di validation
require "validation.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>

    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Login Form</title>
</head>
<body>
    <div class="container">
        <!-- Action balik ke form ini aja ygy -->
        <form action="login_form.php" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Login</p>
            <?php
// Cek apakah dari file validasi login terdapat error
if (count($errors) > 0) {
    ?>
                        <div class="alert alert-danger text-center">
                            <?php
// Jika ada error tampilan error
    foreach ($errors as $showerror) {
        echo $showerror;
    }
    ?>
                        </div>
                        <?php
}
?>
            <div class="input-group">
                <input type="text" placeholder="Username" name="username" value="" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" value="" required>
            </div>
            <div class="input-group">
                <button name="kirim_login" class="btn">Login</button>
            </div>
            <p class="login-register-text">Don't have an account? <a href="register.php">Register</a></p>
            <br>
            <p class="login-register-text"><a href="forgot_pass_form.php">Forgot Password?</a></p>
        </form>
    </div>
</body>
</html>
