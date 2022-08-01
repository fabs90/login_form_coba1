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
?>