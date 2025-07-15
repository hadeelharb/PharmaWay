<?php

include 'connect.php';

session_start();
session_unset();
session_destroy();

header('location:http://localhost/pharmaway/admin/admin_login.php');

?>