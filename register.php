<?php
// Sambungkin dengan file validation
// Sehingga variabel2 di form register bisa dipake di validation
require "validation.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style_register.css">

    <title>Register Form</title>
</head>
<body>
    <div class="container">
        <form action="register.php" method="POST" class="login-email">
            <p class="register-text" style="font-size: 2rem; font-weight: 800;">Register</p>
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
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <input type="text" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <button type="submit" name="kirim_regist" class="btn">Register</button>
            </div>
            <p class="login-register-text">Already have an account? <a href="login_form.php">Back To Login</a></p>
            <br>

        </form>
    </div>
</body>
</html>
<?php
