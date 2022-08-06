<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function kirim_email($email_penerima, $nama_penerima, $judul_email, $isi_email)
{
    $email_pengirim = "freebiesfruits@gmail.com";
    $nama_pengirim = "noreply";

//Load Composer's autoloader
// getcwd berfrungsi untuk mendapatkan direktori sekarang, otomatis dicariin
    require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $email_pengirim; //SMTP username
        $mail->Password = 'hazvuryyfkrofqnj'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email_pengirim, $nama_pengirim);
        $mail->addAddress($email_penerima, $nama_penerima); //Add a recipient/Penerima

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $judul_email;
        $mail->Body = $isi_email;

        $mail->send();
        return "Success";
    } catch (Exception $e) {
        return "Failed to sent";
    }
}
