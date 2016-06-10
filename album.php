<?php
require_once "common.php";
checkSession();
$reqs=requestCheck();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Photos</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
	<link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="common.css">
	<style type="text/css">
	.Img{
		width:100px;
		height:100px;
	}
	.pic-buttons{
		margin-bottom:5px;
		padding:0px;
	}
	.fullb{
		display:inline-block;
		width:60%;
		margin-top:3px;
	}
	.smT{
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

	<div class="container">
		<div class="row">
			<?php
			$con=new mysqli("127.0.0.1","Noor","Noor","network");
				if(isset($_POST)){
					if(isset($_POST['personId'])){
						if($_POST['personId'] == $_SESSION['userId']){
							echo "<h1>Your Photos</h1></div>";
						}
						else{
							$userInfoResult=$con->query("SELECT * FROM users WHERE userId=".$con->real_escape_string($_POST['personId']));
							$userInfoResult->data_seek(0);
							$userInfoRow=$userInfoResult->fetch_array(MYSQLI_ASSOC);
							
							echo "<h2>".$userInfoRow['firstName']." ".$userInfoRow['lastName']."'s Photos</h2></div>";
						}
							$result=$con->query("SELECT * FROM picture WHERE userId=".$con->real_escape_string($_POST['personId']));
												
						if($result->num_rows > 0){
							$c=0;
							echo "<div class='row'>";
							for($i=0; $i < $result->num_rows; $i++) { 
								if($c==0)
									echo "<div class='col s12'>";
								echo "<div class='col s4'>";
								$result->data_seek($i);
								$row=$result->fetch_array(MYSQLI_ASSOC);
								echo "<div class='col s6 push-s2'><img src='data:".$row['type'].";base64,".base64_encode($row['picture'])."' class='Img'></div>";
								if($row['userId'] == $_SESSION['userId'])
									echo "<div class='col s12 pic-buttons'>
											<form>
												<a onclick='makeProPic(this)' class='#40c4ff light-blue accent-2 waves-effect waves-light btn fullb smT' title='Click to make the picture your profile picture'>Profile Picture</a>
												<a onclick='deletePic(this)' class='#f44336 red waves-effect waves-light btn fullb'>Delete</a>
												<input type='hidden' name='pictureId' value='".$row['pictureId']."' >
											</form>
										</div>
										</div>";
								if($c==2){
									echo "</div>";
									$c=0;
								}
								else
									$c++;
							}
							echo "</div>";
						}
						
					}
				}
			?>
		</div>
	</div>

	<script src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
</body>
</html>