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

	    html {
	    	font-family: GillSans, Calibri, Trebuchet, sans-serif;
	    }

	    .friends{
	    	margin-top:10px;
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
		<div class="col s12 ">
			<h2>Likes</h2>
		</div>
		<div class="col s12 ">
			<?php
				$con=new mysqli("127.0.0.1","Noor","Noor","network");
				if($con!=null){
					if(isset($_GET)){
						if(!empty($_GET['postId']))
						$postId=$con->real_escape_string($_GET['postId']);
						$result=$con->query("SELECT * FROM post_likes WHERE postId=".$postId);
						if($result->num_rows <= 0)
							echo "<div class='col s8 push-s3'><h2>No likes on this post so far</h2></div>";
						for ($i=0; $i < $result->num_rows; $i++) { 
							echo "<div class='col s12 m12 l3 friends'>";
							$result->data_seek($i);
							$row=$result->fetch_array(MYSQLI_ASSOC);
							//printing the image [1]
							$dpRes=$con->query("SELECT * FROM users WHERE userId=".$row['likerId']);

							if($dpRes->num_rows > 0){
								$dpRes->data_seek(0);
								$dpRow=$dpRes->fetch_array(MYSQLI_ASSOC);
								$picTableResult=$con->query("SELECT * FROM picture WHERE pictureId=".$dpRow['dpId']);
								$picTableResult->data_seek(0);
								$picTableRow=$picTableResult->fetch_array(MYSQLI_ASSOC);
								echo "<img src='data:".$picTableRow['type'].";base64,".base64_encode($picTableRow['picture'])."' class='userImg'>";
								$picTableResult->close();
								$dpRes->close();
							}
							else{
								if($row['gender']==1)
									$img="default/male.png";
								else
									$img="default/female.png";
								echo "<img src='".$img."' class='userImg' >";
							}
							//[1]
							$nameRes=$con->query("SELECT * FROM users WHERE userId=".$row['likerId']);
							$nameRes->data_seek(0);
							$nameRow=$nameRes->fetch_array(MYSQLI_ASSOC);
							echo "<a href='profile.php?personId=".$row['likerId']."' class='nameSpanPost'>".$nameRow['firstName']." ".$nameRow['lastName']."</a>
							</div>";
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