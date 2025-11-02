 
<?php
session_start();
session_destroy();
header("Location: /news-portal/auth/login.php");
