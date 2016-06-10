<?php
require_once "common.php";
checkSession();
$reqs=requestCheck();
?>
<!DOCTYPE html>
<html>
<head>
	<title>network</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
	<link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="common.css">
	<style type="text/css">
		<h2{
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
	
	<div class="container">
		<?php
			$con=new mysqli("localhost","Noor","Noor","network");
			if($con!=null){
				if(isset($_POST)){
					if(isset($_POST['personId'])){
						$treatedId=$con->real_escape_string($_POST['personId']);
						$chkchk=$con->query("SELECT * FROM friend_list WHERE (userId=".$treatedId." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$treatedId.")");
						if($chkchk->num_rows > 0 || $treatedId==$_SESSION['userId']){
							$showFriendResult=$con->query("SELECT * FROM friend_list WHERE userId=".$treatedId." OR friendId=".$treatedId);

							echo"<div class='row'>";
							if($treatedId==$_SESSION['userId'])
								echo"<div class='col s12'><h1>Your Friends</h1></div>";
							else{
								$nameRes=$con->query("SELECT firstName,lastName FROM users WHERE userId=".$treatedId);
								$nameRes->data_seek(0);
								$nameRow=$nameRes->fetch_array(MYSQLI_ASSOC);
								echo"<div class='col s12'><h1>".$nameRow['firstName']." ".$nameRow['lastName']."'s Friends</h1></div>";
							}
							echo"</div>";
							echo"<div class='row'>";

							$b=0;
							for($a=0 ; $a < $showFriendResult->num_rows ; $a++){
								if($b==0)
									echo "<div class='col s12 '>";

								$showFriendResult->data_seek($a);
								$showFriendRow=$showFriendResult->fetch_array(MYSQLI_ASSOC);
								if($showFriendRow['userId']==$treatedId)
									$userResult=$con->query("SELECT * FROM users WHERE userId=".$showFriendRow['friendId']);
								elseif($showFriendRow['friendId']==$treatedId)
									$userResult=$con->query("SELECT * FROM users WHERE userId=".$showFriendRow['userId']);
									$userRow=$userResult->fetch_array(MYSQLI_ASSOC);
									echo "<div class='col s4'>";
									if($userRow['dpId']!=NULL){
										$picResult=$con->query("SELECT * FROM picture WHERE pictureId=".$userRow['dpId']);
										if($picResult->num_rows > 0){
											$picRow = $picResult->fetch_array(MYSQLI_ASSOC);
											
											echo"<img src='data:".$picRow['type'].";base64,".base64_encode($picRow['picture'])."' class='userImg'>";
										}
									}
									else{
										if($userRow['gender'] ==1)
											echo "<img src='default/male.png' class='userImg'>";
										else
											echo "<img src='default/female.png' class='userImg'>";
									}	
								echo"<a href='profile.php?personId=".$userRow['userId']."' class='nameSpanPost'>".$userRow['firstName']." ".$userRow['lastName']."</a>
												  <div class='col s12'>
												  <form method='post' action='unfriend.php'>
												  <input type='hidden' name='unfriendId' value='".$userRow['userId']."' />
												  <a onclick='unfriend(this)' class='#40c4ff light-blue accent-2 waves-effect waves-light btn fullb smT' >Unfriend</a>
												  </form>
												  </div>
												  </div>";
								if($b==2){
									$b=0;
									echo "</div>";
								}
								if($b<3)
									$b++;
							}
						}
					}//isset($_POST['personId'])
				}//isset($_POST)
			}//$con!=null
			//echo "</div></div>";//contsiner and row closing tags
		?>
		</div></div>
		<script src='common.js'></script>
		<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
	</body>
</html>