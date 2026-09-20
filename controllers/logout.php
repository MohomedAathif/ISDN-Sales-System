<?php
session_start();
setcookie("remember_user", "", time() - 3600, "/");
session_destroy();

header("Location: ../views/auth/login.php");
exit();