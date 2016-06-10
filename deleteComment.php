<?php
require_once "common.php";
checkSession();

$con=new mysqli("127.0.0.1","Noor","Noor","network");
if($con==NULL){
	echo"shit";
	die("no connection");
}

if(isset($_POST)){
	if(!empty($_POST['commentId'])){
		$userCommentResult=$con->query("DELETE FROM post_comments WHERE commentorId=".$_SESSION['userId']." AND postCommentId=".$_POST['commentId']);
		if( $con->affected_rows > 0 )
			echo "d";
		$res=$con->query("SELECT COUNT(*) FROM post_comments WHERE postId=".$_POST['postId']);
		$rr=$res->fetch_array(MYSQLI_NUM);
		echo $rr[0];		
	}
}else{echo"couldn't delete".$_POST['postId'];}




?>