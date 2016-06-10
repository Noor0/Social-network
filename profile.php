<?php
require_once "common.php";
checkSession();
$reqs=requestCheck();
if(!isset($_GET['personId']))
	header("Location:home.php");
$theDp="";
$dpType="";
if(isset($_GET)){
	if(!empty($_GET['personId'])){
		$con=new mysqli("127.0.0.1","Noor","Noor","network");
		$personId=$con->real_escape_string($_GET['personId']);
		if($con!=null){
			$userResult=$con->query("SELECT * FROM users WHERE userId=".$personId);
			$userResult->data_seek(0);
			$userRow=$userResult->fetch_array(MYSQLI_ASSOC);
			$name=$userRow['firstName']." ".$userRow['lastName'];
			$gender=$userRow['gender']==1 ?  "Male":"Female";
			$country=$userRow['country']==null ? "":$userRow['country'];
			$city=$userRow['city']==null ? "":$userRow['city'];
			$aboutMe=$userRow['city'];			
		}
	}	

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $name; ?></title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
	<link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="common.css">
	<style>
		.profilePcture{
			margin: auto;
			margin:0px;
			width:100%;
			height:100%;
			background-color:red;
			cursor:pointer;
		}
		.userName{
			font-weight:bold;
			text-align:center;
			font-size:20px;
			margin:3px;
		}
		.butt{
			text-align:center;
			width:100%;
			margin-top:3px;
		}
		.bio{
			text-align:center;
			font-weight:bold;
			margin:0px;
			padding:3px;
			line-height:10px;
		}

		#chbut{
			display:none;
			width:100%;
		}

		.fullb{
			display:inline-block;
			width:100%;
			margin-top:3px;
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
				global $already;

				echo"<div class='col s6 m4 l4 push-l4 push-s3 push-m4'>";
				if($_GET['personId']==$_SESSION['userId']){
					if($_SESSION['dpType']!="no")
						echo "<form action='changePicture.php' method='post' enctype='multipart/form-data'>
							<img src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."' onclick='change(this)' class='profilePcture'>
							<input type='file' id='picpic' style='display:none;' name='image' accept='image/jpeg, image/png'>
							<input class='#40c4ff light-blue accent-2 btn' type='submit' name='gogo' value='change' id='chbut'>
							</form>";
					else
						echo "<form action='changePicture.php' method='post' enctype='multipart/form-data'>
						<img src='".$_SESSION['dp']."' onclick='change(this)' class='profilePcture'>
						<input type='file' id='picpic' style='display:none;' name='image' accept='image/jpeg, image/png'>
						<input class='#40c4ff light-blue accent-2 btn' type='submit' name='gogo' value='change' id='chbut'>
					</form>";
				}
				else{
					$dpId=$userRow['dpId'];
					if($dpId!=NULL){
						$dpResult=$con->query("SELECT * FROM picture WHERE pictureId=".$dpId);
						if($dpResult->num_rows > 0){
							$dpResult->data_seek(0);
							$dpRow=$dpResult->fetch_array();
							$theDp=$dpRow['picture'];
							$dpType=$dpRow['type'];
							echo"<img src='data:".$dpType.";base64,".base64_encode($theDp)."' class='profilePcture'>";
						}
					}
					else{
							$dpType="no";
							if($userRow['gender']==1)
								$theDp="default/male.png";
							else
								$theDp="default/female.png";

							echo"<img src='".$theDp."' class='profilePicture'>";
						}
				}
				echo "<p class='userName'>".$name."</p>";
				if($country!="")
					echo "<p class='bio'>".$country."</p>";
				if($city!="")
					echo "<p class='bio'>".$city."</p>";
				
				echo "<p class='bio'>".$gender."</p>";
				//echo "</div>";//closing of the first echoed column
					
				//------------------------------------------------------------------------
				$pending=false;
				$penChk=$con->query("SELECT * FROM friend_request WHERE (senderId=".$personId." AND recieverId=".$_SESSION['userId'].") OR (senderId=".$_SESSION['userId']." AND recieverId=".$personId.")");
				
				if($penChk->num_rows==1)
					$pending=true;
				//------------------------------------------------------------------------
				if($pending!=true){
					$already=false;
					$alChk=$con->query("SELECT * FROM friend_list WHERE (userId=".$personId." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$personId.")");
						
					if($alChk->num_rows==1)
						$already=true;	
				}
				//------------------------------------------------------------------------
				if($_SESSION['userId']!=$personId)
					if($pending==true){
						echo"<div>
								<input type='hidden' name='personId' value=".$personId."/>
								<a class='waves-effect waves-light btn butt #40c4ff light-blue accent-2'>
									<i onclick='sendRequest(this)' class='material-icons left'>face</i>Pending
								</a>
							</div>
						<div>
							<a class='waves-effect waves-light btn butt #40c4ff light-blue accent-2'>
								<i class='material-icons left'>message</i>Send Message
							</a>
						</div>";
					}
					else if($already){
						echo"<div>
								<a class='waves-effect waves-light btn butt #40c4ff light-blue accent-2'>
									<i class='material-icons left'>message</i>Send Message
								</a>
							</div>";
					}else	
						echo"<div>
							<form action='sendRequest.php' method='post'>
								<input type='hidden' name='personId' value=".$personId.">
								<a onclick='sendRequest(this)' class='waves-effect waves-light btn butt #40c4ff light-blue accent-2'>
									<i class='material-icons left'>face</i>Add Freind
								</a>
							</form>
						</div>
						<div>
							<a class='waves-effect waves-light btn butt #40c4ff light-blue accent-2'>
								<i class='material-icons left'>message</i>Send Message
							</a>
						</div>";
						
						echo "</div></div>";
						if($already || $_GET['personId']==$_SESSION['userId']){
							echo "<div class='row'>
									<div class='col s12 m8 push-m2 l4 push-l4'><div class='col s12 m6 l6'>
									<form action='album.php' method='POST'>
										<a onclick='showMe(this)' class='#00c853 green accent-4 waves-effect waves-light btn fullb'>
											<i class='material-icons left'>photo_album</i>Photos
										</a>
										<input type='hidden' name='personId' value='".$_GET['personId']."'>
										</form>
									</div>
									<div class='col s12 m6 l6'>
										<form action='friendList.php' method='POST'>
											<a onclick='showMe(this)' class='#00c853 green accent-4 waves-effect waves-light btn fullb'>
												<i class='material-icons left'>people</i>Friends
											</a>
											<input type='hidden' name='personId' value='".$_GET['personId']."'>
										</form>
									</div>
								   </div>
								   </div>";
						}

			echo "<div class='row'>";			
			$postResults=$con->query("SELECT * FROM posts WHERE userId=".$personId." ORDER BY timeStamp DESC");	
			for($a=0; $a < $postResults->num_rows ; $a++){
				$postResults->data_seek($a);
				$postRow=$postResults->fetch_array(MYSQLI_ASSOC);
				$likeNumbers=0;
				$commentsNumber=0;

				$likeResult=$con->query("SELECT COUNT(*) FROM post_likes WHERE postId=".$postRow['postId']);
				$likeResult->data_seek(0);
				$likeRow=$likeResult->fetch_array(MYSQLI_NUM);
				$likeNumbers=$likeRow[0];
				$likeResult->close();

				$commentResult=$con->query("SELECT COUNT(*) FROM post_comments WHERE postId=".$postRow['postId']);
				$commentResult->data_seek(0);
				$commentRow=$commentResult->fetch_array(MYSQLI_NUM);
				$commentsNumber=$commentRow[0];
				$commentResult->close();

				// checking if already liked
				$likeCheck=$con->query("SELECT * FROM post_likes WHERE likerId=".$_SESSION['userId']." AND postId=".$postRow['postId']);
				if($likeCheck->num_rows==1)
					$chkd='chkd';
				else
					$chkd='unchkd';
				
				echo "<div class='card-panel col l6 s12 m12 push-l3 posts'>";
				if($postRow['userId']==$_SESSION['userId']){
					echo"<form>
						<i title='Delete Post' onclick='deletePost(this)' class='material-icons md-18 delete'>delete</i>
						<input type='hidden' name='hiddenPostId' value='".$postRow['postId']."' />
					</form>";
					if($_SESSION['dpType']!="no")
						echo "<img src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."' class='userImg'>";
					else
						echo "<img src='".$_SESSION['dp']."' class='userImg'>";
					echo"<a class='nameSpanPost'>".$name."</a>
					<span class='dateTime'>".$postRow['timeStamp']."</span>
					<div class='col s12 posts'>
						<pre class='stsMsg'>".$postRow['postMessage']."</pre>
					</div>
					<div class='col s12'>
						<form action='comment.php' method='get'>
							<input type='hidden' name='hiddenPostId' value='".$postRow['postId']."' />
							<i onclick='likeIt(this)' class='material-icons md-24 ".$chkd."'>❤</i>
							<span class='number' onclick='showLikes(this)'>".$likeNumbers." likes</span >
								<i onclick='goToComments(this)' class='material-icons md-24 cmnt'>✐</i>
							<span>".$commentsNumber." comments</span>
						</form>
					</div>
					</div>";
					}
					else{
						if($dpType!="no")
							echo "<img src='data:".$dpType.";base64,".base64_encode($theDp)."' class='userImg'>";
						else
							echo "<img src='".$theDp."' class='userImg'>";
						
						echo "<a class='nameSpanPost'>".$name."</a>
						<span class='dateTime'>".$postRow['timeStamp']."</span>
						<div class='col s12 posts'>
							<pre class='stsMsg'>".$postRow['postMessage']."</pre>
						</div>
						<div class='col s12'>
							<form action='comment.php' method='get'>
								<input type='hidden' name='hiddenPostId' value='".$postRow['postId']."' />
								<i onclick='likeIt(this)' class='material-icons md-24 ".$chkd."'>❤</i>
								<span class='number'>".$likeNumbers." likes</span >
									<i onclick='goToComments(this)' class='material-icons md-24 cmnt'>✐</i>
								<span>".$commentsNumber." comments</span>
							</form>
						</div>
						</div>";
				}
			}
			echo "</div>";
			}
		?>
	</div>




	<script type="text/javascript">
		
		var request = new XMLHttpRequest();
		
		function goToComments(pen){
			var id=pen.parentNode.submit();
			console.log(pen.parentNode.nodeName);
		}

		

	</script>
	<script src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
</body>
</html>