<?php

require_once "common.php";
checkSession();


if($_POST){
	if(isset($_POST['postId'])){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		//checking if already liked if yes then delete
		$alreadyResult=$con->query("SELECT * FROM post_likes WHERE postId=".$_POST['postId']." AND likerId=".$_SESSION['userId']);
		if($alreadyResult->num_rows==1){
			$alreadyResult->data_seek(0);								 
			$alreadyRow=$alreadyResult->fetch_array(MYSQLI_ASSOC);
			$con->query("DELETE FROM post_likes WHERE postId=".$_POST['postId']." AND likerId=".$_SESSION['userId']);
			//$con->query("DELETE FROM post_likes WHERE postLikeId=".$alreadyRow['postLikeId']);
			echo "d";
			$alreadyResult->close();
		}
		else{
			$getPersonId=$con->query("SELECT userId FROM posts WHERE postId=".$_POST['postId']);
			if($getPersonId->num_rows>=1){
				$getPersonId->data_seek(0);
				$personId=$getPersonId->fetch_assoc()['userId'];
				$chk=$con->query("SELECT * FROM friend_list WHERE (userId=".$personId." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$personId.")");
				if($chk->num_rows==1 || $personId==$_SESSION['userId']){
					//inserting a like
					$likeStatement=$con->prepare("INSERT INTO post_likes VALUES(?,?,?)");
					$likerId=$_SESSION['userId'];
					$postId=$_POST['postId'];
					$postLikeId=NULL;
					$likeStatement->bind_param("iii",$likerId,$postId,$postLikeId);
					$likeStatement->execute();
				}else{echo "n";}
			}
		}	
		echo likesNumber($con);

		$con->close();

	}else echo "fault";
}else{
	header("Location:home.php");
}







function likesNumber($con){
	//getting like numbers
	$likeNumberResult=$con->query("SELECT COUNT(*) FROM post_likes WHERE postId=".$_POST['postId']);
	if($likeNumberResult!=NULL){
		$likeNumberResult->data_seek(0);
		$likeNumberRow=$likeNumberResult->fetch_array(MYSQLI_NUM);
		return $likeNumberRow[0];
	}	
}

?>