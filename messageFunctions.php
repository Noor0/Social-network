<?php
require_once "common.php";
checkSession();
if(isset($_POST)){
	if($_POST['opCode']==1){
		//set current person
		if(!empty($_POST['person'])){
			$con = mysqli("localhost","Noor","Noor","network");
			if($con==false)
				die("no connection");
			$result=$con->query("SELECT * FROM friend_list WHERE (userId=".$_SESSION['userId']." AND friendId=".$_POST['person'].") OR (userId=".$_POST['person']." AND friendId=".$_SESSION['userId'].")");
			if($result->num_rows == 1){
				$_SESSION['currentPerson']=$_POST['person'];
				$result->close();
				$con->close();
			}
		}
	}
	if($_POST['opCode']==2){
		//insert message in database
		$con = mysqli("localhost","Noor","Noor","network");
		if($con==false)
				die("no connection");
		$prpdStmnt=$con->prepare("INSERT INTO messages VALUES (?,?,?,?)");
		$id=NULL;
		$senderId=$_SESSION['userId'];
		$recieverId=$_SESSION['currentPerson'];
		$message=$_POST['theMesage'];
		$resultOfInsert=$prpdStmnt->execute();
		if($resultOfInsert->affected_rows > 0){
			echo "sent";
		}
		else{
			echo "notsent";
		}
		$resultOfInsert->close();
		$con->close();
	}

	if($_POST['opCode']==3){
		//scroll fetch
	}

	if($_POST['opCode']==4){
		//initial fetch
		$con = mysqli("localhost","Noor","Noor","network");
		if($con==false)
				die("no connection");

		$resultInital=$con->query("SELECT * FROM messages WHERE (senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION['currentPerson'].") OR (senderId=".$_SESSION['currentPerson']." AND recieverId=".$_SESSION['userId'].")");

		//fetching last messages from database
		/*$resultInital=$con->query("SELECT * FROM (SELECT * FROM messages WHERE (senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION['currentPerson'].") OR (senderId=".$_SESSION['currentPerson']." AND recieverId=".$_SESSION['userId'].") ORDER BY id DESC LIMIT 10)AS new ORDER BY id ASC");
		if($resultInital->num_rows > 0 ){
			for ($i=0; $i < $resultInital->num_rows; $i++) { 
				
			}
		}*/


	}


}
?>
