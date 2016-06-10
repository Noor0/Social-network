<?php
function checkSession(){
	try{
		session_start();
		if(!isset($_SESSION['userId']))
			header("Location:index.php");
		else return true;
		if(empty($_SESSION['userId']) && empty($_SESSION['userName']))
			header("Location:index.php");
		else return true;
	}catch(Exception $e){
		echo "You are not logged in <br> <a href='index.php'></a>";
		die($e.getMessage());
	}
}

function requestCheck(){
$tempcon=new mysqli("127.0.0.1","Noor","Noor","network");
$rrr=$tempcon->query("SELECT COUNT(*) FROM friend_request WHERE recieverId=".$_SESSION['userId']);
if($rrr->num_rows > 0){
	$tt=$rrr->fetch_array(MYSQLI_NUM);
	return $tt[0];
}
$rrr->close();
}

?>