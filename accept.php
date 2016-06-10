<?php
	require_once "common.php";
	checkSession();

	if(isset($_POST)){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		$ii=NULL;
		$friendId=$_POST['senderId'];
		$userId=$_SESSION['userId'];
		$stat=$con->prepare("INSERT INTO friend_list VALUES(?,?,?)");
		$stat->bind_param("iii",$ii,$userId,$friendId);
		if(!($con->begin_transaction()))
			die("a");
		else{
			$con->query("DELETE FROM friend_request WHERE recieverId=".$_SESSION['userId']." AND senderId=".$friendId);
			$stat->execute();
			$con->commit();
			echo "done";
		}
	}

?>