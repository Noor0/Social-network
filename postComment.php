<?php

require_once "common.php";
checkSession();

if(isset($_POST)){
	if(!empty($_POST['commentField'])){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		//if($con==null)
			//die("fuck you bhai koi masla hai");
		$cmntPrepare=$con->prepare("INSERT INTO post_comments VALUES(?,?,?,?)");
		$commentorId=$_SESSION['userId'];
		$msg=$con->real_escape_string($_POST['commentField']);
		$commentMsg=sanitizePost($msg);
		$postCommentId=NULL;
		$postId=$_POST['hiddenPostId'];//getting it from comment.php
		$cmntPrepare->bind_param("isii",$commentorId,$commentMsg,$postCommentId,$postId);
		$cmntPrepare->execute();
	}
	echo "<script>alert(".$commentMsg.");</script>";
	header("Location:comment.php?hiddenPostId=".$_POST['hiddenPostId']);
}










function sanitizePost($str){
	$str=stripslashes($str);
	$str=htmlspecialchars($str);
	return $str;
}





?>