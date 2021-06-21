<?php
session_start();
$_SESSION['email']=null;
$_SESSION['name']=null;
$new_url = 'https://lab2W/HeadNoAuthPublic.php';
header('Location: ' . $new_url);
exit();

