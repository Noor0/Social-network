<?php
require_once "common.php";
checkSession();
session_unset();
session_destroy();
setcookie(session_name(),"",time()-13476);
header("Location:index.php");
?>