<?php
session_start();
session_unset();    // optional, clears session vars
session_destroy();  // destroys session
header("Location: staff-login.php");
exit();
