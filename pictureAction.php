<?php
require_once "common.php";
checkSession();
$con=new mysqli("127.0.0.1","Noor","Noor","network");
if($con==null)
	die("sd");
$pictureId=$con->real_escape_string($_POST['pictureId']);
if(isset($_POST)){
	if(!empty($_POST['code']) && !empty($_POST['pictureId'])){
		if($_POST['code']==1){
			$result=$con->query("SELECT * FROM picture WHERE userId=".$_SESSION['userId']." AND pictureId=".$pictureId);
			if($result->num_rows == 1){
				$con->query("UPDATE users SET dpId=".$pictureId." WHERE userId=".$_SESSION['userId']);
				$result->data_seek(0);
				$row=$result->fetch_array(MYSQLI_ASSOC);
				$_SESSION['dpType']=$row['type'];
				$_SESSION['dp']=$row['picture'];
				echo "done1";
			}
		}
		if($_POST['code']==2){
			$con->begin_transaction();
			$con->query("DELETE FROM picture WHERE userId=".$_SESSION['userId']." AND pictureId=".$pictureId);
			if($con->affected_rows >= 1){
				$chkResult=$con->query("SELECT * FROM users WHERE userId=".$_SESSION['userId']);
				if($chkResult->num_rows > 0){
					$chkResult->data_seek(0);
					$chkRow=$chkResult->fetch_array(MYSQLI_ASSOC);
					if($chkRow['dpId']==$pictureId){
						$con->query("UPDATE users SET dpId=NULL WHERE userId=".$_SESSION['userId']);
						if($con->affected_rows >= 1){
							$con->commit();
							echo "done2";
							$_SESSION['dpType']="no";
							if($chkRow['gender']==1)
								$_SESSION['dp']="default/male.png";		
							else
								$_SESSION['dp']="default/female.png";
						}	
						else{
							$con->rollback();
						}
					}
					else{
						$con->commit();
						echo "done2";
					}
				}
				$chkResult->close();
			}
			else{
				$con->rollback();
			}

		}
	}
}
$con->close();
?>