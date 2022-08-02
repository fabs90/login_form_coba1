<?php
session_destroy();
session_unset();
$_SESSION = [];

header("Location:login_form.php");
