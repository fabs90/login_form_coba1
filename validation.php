<?php
// Sambungkan dengan koneksi db di halaman connection
require "connection.php";

// Jika apakah tombol button kirim sudah di klik
if (isset($_POST["kirim"])) {
    // Jika form username dan password TIDAK kosong
    if (!empty($_POST["username"]) and !empty($_POST["password"])) {

        $username = mysqli_real_escape_string($connection, $_POST['username']);

        // Query sql
        $result = mysqli_query($connection, "SELECT * FROM login WHERE username = '$username'") or die(mysqli_error($connection));

        // Cek apakah ada baris data yg sama dengan query
        $numrows = mysqli_num_rows($result);

        // Jika ada baris dari table yg sama
        if ($numrows > 0) {
            // Ubah data yg ada di db menjadi assoc array
            while ($row = mysqli_fetch_array($result)) {
                $db_username = $row['username'];
                $db_password = $row['password'];
                $db_email = $row['email'];
            }

            // masukan data dari form yg diinput user ke dalam variabel
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $password = mysqli_real_escape_string($connection, password_verify($_POST['password'], $db_password));

            // Cek apakah data di db sama dengan data di form yg diinput user
            if ($username == $db_username and password_verify($password, $db_password)) {
                // Nyalain session
                session_start();
                // Set variabel session username dengan $username
                $_SESSION['username'] = $username;
                // Pindah page
                header("Location:home.php");

            }
        } else {
            echo "<script type='text/javascript'> alert('Wrong username/Password!'); document.location.href='login_form.php';</script>";
        }
    }
}
