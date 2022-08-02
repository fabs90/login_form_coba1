<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location:login_form.php");
}

echo "Ini form Home";
?>

<a href="logout.php">Logout</a>
