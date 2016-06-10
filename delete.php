<?php

require_once "common.php";
checkSession();

$con=new mysqli("127.0.0.1","Noor","Noor","network");
if($con==NULL){
	echo"shit";
	die("no connection");
}

if(isset($_POST)){
	if(!empty($_POST['postId'])){
		try{
			$started=$con->begin_transaction();
			if($started){
				$userPosts=$con->query("DELETE FROM posts WHERE userId=".$_SESSION['userId']." AND postId=".$_POST['postId']);
				$con->query("DELETE FROM post_comments postId=".$_POST['postId']);
				$con->query("DELETE FROM post_likes postId=".$_POST['postId']);
			}else throw new Exception("problem in transaction");
			$con->commit();
			echo "d";
		}
		catch(Exception $e){
			$con->rollback();
			echo $e->getMessage();
		}
	}else{echo"couldn't delete".$_POST['postId'];}
}

?>