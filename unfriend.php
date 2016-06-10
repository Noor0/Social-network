<?php
require_once "common.php";
checkSession();

if(isset($_POST)){
	if(!empty($_POST['unfriendId'])){
		//unfriend id is a user id and can only be a number
		if(preg_match("/^[0-9]+$/",$_POST['unfriendId'])){
			$con=new mysqli("localhost","Noor","Noor","network");
			if(!$con)
				die(' ');
			else{
				$treatedId=$con->real_escape_string($_POST['unfriendId']);
				$result=$con->query("SELECT * FROM friend_list WHERE (userId=".$treatedId." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$treatedId.")");
				if($result->num_rows == 1){
					$result->close();
					$delReslut=$con->query("DELETE FROM friend_list WHERE (userId=".$treatedId." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$treatedId.")");
					if($delReslut->affected_rows() ==1) echo"done"; else echo"couldn't unfriend";
					$delReslut->close();
				}
				$con->close();
			}
		}
	}
}
header("Location:friendList.php");
?>