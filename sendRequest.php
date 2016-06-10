<?php
require_once "common.php";
checkSession();

if(isset($_POST)){
	if(!empty($_POST['personId'])){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		if($con==null)
			die("");
		$personId=$con->real_escape_string($_POST['personId']);
		$stat=$con->prepare("INSERT INTO friend_request VALUES(?,?,?)");
		$userId=$_SESSION['userId'];
		$id=NULL;
		$stat->bind_param("iii",$id,$userId,$personId);
		$stat->execute();
		header("Location:profile.php?personId=".$_POST['personId']);
	}
}
?>