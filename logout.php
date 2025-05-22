<?php
session_start();
session_destroy();
setcookie('remembered_name', '', time() - 3600, "/");
header('Location: login.php');
exit;