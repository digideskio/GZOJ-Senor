<?php 
require_once "include/db_info.inc.php";
unset($_SESSION['user_id']);
session_destroy();
echo '{"no":0,"err":"Success"}';
?>
