<?php
require_once "common.php";
checkSession();

if(isset($_POST['gogo'])){
	if(isset($_FILES)){
		if($_FILES['image']['size']<=0)
			echo "<h2>Select an image first</h2>";
		else{
			if($_FILES['image']['size']>3145728){
				echo "<h2>Image should not be greater than 5mb</h2>";
			}
			else{
				if(preg_match('#^image/jpeg$#',$_FILES['image']['type']) || preg_match('#^image/png$#',$_FILES['image']['type'])){
					$con=new mysqli("localhost","Noor","Noor","network");
					$type=$con->real_escape_string($_FILES['image']['type']);
					move_uploaded_file($_FILES['image']['tmp_name'],$_FILES['image']['name']);
					$id=NULL;
					$uploaderId=$_SESSION['userId'];
					$picBlob=file_get_contents($_FILES['image']['name']);
					unlink($_FILES['image']['name']);
					$stat=$con->prepare("INSERT INTO picture VALUES(?,?,?,?)");
					$stat->bind_param("iiss",$id,$uploaderId,$picBlob,$type);
					$con->begin_transaction();
					$stat->execute();
					$con->query("UPDATE users SET dpId=".$stat->insert_id." WHERE userId=".$_SESSION['userId']);
					$con->commit();
					$res=$con->query("SELECT * FROM picture WHERE pictureId=".$stat->insert_id);
					$res->data_seek(0);
					$row=$res->fetch_array(MYSQLI_ASSOC);
					$_SESSION['dp']=$row['picture'];
					$_SESSION['dpType']=$type;
					header("Location:profile.php?personId=".$_SESSION['userId']);
				}/*else{echo "Select either png or jpeg format image";}*/
			}
		}
	}
}
?>
