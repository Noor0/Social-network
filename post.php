<?php

require_once "common.php";
checkSession();


try{
	if($_POST){
		if(!empty($_POST['status'])){
			//$status=preg_replace("\r","",$_POST['status'].trim());
			$status=$_POST['status'].trim();
			$status=sanitizePost($_POST['status']);
			$con=new mysqli("127.0.0.1","Noor","Noor","network") ;
			//$status=$con->real_escape_string($status);
			$statusStatement=$con->prepare("INSERT INTO posts VALUES(?,?,?,?)");
			$pId=NULL;
			$userId=$_SESSION['userId'];
			//$date=NULL;//date("YYYY-MM-DD",time());
			//$time=NULL;//date("H:i:s",time());
			$stamp=date('Y-m-d G:i:s',time());
			$statusStatement->bind_param("iiss",$userId,$pId,$status,$stamp);
			$statusStatement->execute();
			$statusStatement->close();
			$con->close();
		}

	}
}catch(Exception $e){
	die($e.getMessage());
}
finally{
	header("Location:home.php");
}





function sanitizePost($str){
	$str=stripslashes($str);
	$str=htmlspecialchars($str);
	return $str;
}

?>