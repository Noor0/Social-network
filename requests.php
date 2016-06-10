<?php
require_once "common.php";
checkSession();

$reqs=requestCheck();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Friend Requests</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
	<link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="common.css">
	<style>
		.senderImg{
			height:200px;
			background-color:red;
			width:200px;
		}

		.nono{
			text-align:center;
		}

		.reqStatus{
			text-align:center;
		}

	</style>
</head>
<body>
	<div class="row head">
		<div class="navbar-fixed">
    		<nav>
      			<div class="nav-wrapper">	
				<img <?php if($_SESSION['dpType']!="no"){echo "src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."'";}else{echo "src='".$_SESSION['dp']."'";} ?> class="userImg"><span class="nameSpan"><a href='<?php echo "profile.php?personId=".$_SESSION['userId']; ?>' style="color:#01579b;font-weight:bold"><?php echo $_SESSION['userName']?></a></span></img>
				<ul class="headerNav">
					<li title="Setting"><i class="md-24 material-icons">settings</i></li>
					<li title="Logout"><form action="logout.php" method="POST"><i class="md-24 material-icons" onclick="logout(this)">power_settings_new</i></form></li>
					<li title="Messages" onclick="messages()"><i class="md-24 material-icons">chat_bubble</i></li>
					<li title="News Feed" onclick="home()"><i class="md-24 material-icons" >home</i></li>
					<li title="Profile" onclick="profile(this)"><i class="md-24 material-icons" >person</i></li>
					<li title="Request" onclick="requestPage()"><span class="left new-badge"><?php echo $reqs;?></span><i class="md-24 material-icons" >person_add</i></li>
					<input type="hidden" id="idd" value='<?php echo $_SESSION['userId']; ?>'>
				</ul>
				</div>
			</nav>
		</div>
	</div>

	<div class="row">
		<?php
			$con = new mysqli("127.0.0.1","Noor","Noor","network");
			$reqResult=$con->query("SELECT * FROM friend_request WHERE recieverId=".$_SESSION['userId']);
			if ($reqResult->num_rows==0) {
				echo"<h4 class='reqStatus'>You have no new friend requests</h4>";
			}
			echo "<div class='col s12 m12 l4 push-l4'>";
			for($a=0 ; $a < $reqResult->num_rows ; $a++){
				$reqResult->data_seek($a);
				$row=$reqResult->fetch_array(MYSQLI_ASSOC);
				$senderInfo=$con->query("SELECT * FROM users WHERE userId=".$row['senderId']);
				$senderInfoRow=$senderInfo->fetch_array(MYSQLI_ASSOC);
				$senderName=$senderInfoRow['firstName']." ".$senderInfoRow['lastName'];

				echo"<div class='col s12'>
						<div class='col l6 push-l3 s4 push-s3 m4 push-m5'>";

				if($senderInfoRow['dpId']!=NULL){
					if($senderInfoRow['userId']==$_SESSION['userId']){
						if($_SESSION['userId'] != "no"){
							echo "<img src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."' class='senderImg'>";
						}
						//////
					}else{
						$picResult=$con->query("SELECT * FROM picture WHERE pictureId=".$senderInfoRow['dpId']);
						if($picResult->num_rows > 0){
							$picResult->data_seek(0);
							$picRow=$picResult->fetch_array(MYSQLI_ASSOC);
							echo "<img src='data:".$picRow['type'].";base64,".base64_encode($picRow['picture'])."' class='senderImg'>";
						}
						/*else{
							if($senderInfoRow['gender']==1)
								echo"<img src='default/male.png' class='senderImg'>";
							else
								echo"<img src='default/female.png' class='senderImg'>";
						}*/
					}
				}
				else{
					if($senderInfoRow['gender']==1)
						echo"<img src='default/male.png' class='senderImg'>";
					else
						echo"<img src='default/female.png' class='senderImg'>";
				}
				echo"</div>
				<div class ='col s12'>
					<p class='nameSpanPost nono'>
						<a href='profile.php?personId=".$senderInfoRow['userId']."'>".$senderName."</a><br>
					</p>
				</div>
				</div>						
				<form>
					<div class='col s6'>
						<a onclick='accept(this)' class='#00c853 green accent-4 waves-effect waves-light btn full'><i class='material-icons right'>check</i>Accept</a>
					</div>
					<div class='col s6'>
						<a onclick='ignore(this)' class='#f44336 red waves-effect waves-light btn full'><i class='material-icons right'>close</i>Ignore
						</a>
					 </div>
					 <input type='hidden' name='chillz' value=".$senderInfoRow['userId'].">
				</form>";
				}
					
				
			echo "</div>";
		?>
	</div>








	<script src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
</body>
</html>