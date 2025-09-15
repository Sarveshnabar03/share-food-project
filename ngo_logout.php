<?php
session_start();
session_unset();
session_destroy();
header("Location: ngo_login.php");
exit;
