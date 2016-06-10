<?php
	require_once "common.php";
	checkSession();

	if(isset($_POST)){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		$con->query("DELETE FROM friend_request WHERE recieverId=".$_SESSION['userId']." AND senderId=".$con->real_escape_string($_POST['senderId']));
		echo "done";
	}

?>