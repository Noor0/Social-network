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
		.comment{
			margin-top:20px;
		}

		#commentBar{
			margin-top:30px;
		}
		.commentorName{
			float:left;
			padding:0px;
			margin:0px;
			top:20px;
			font-size:16px;
			color:#336E7B;
			font-weight:bold;
		}
		.commentImg{
			float:left;
			width:50px;
			height:50px;
			background-color:red;
		}

		#commentPre{
			margin-top:10px;
			width:98%;
			white-space: pre-wrap;
		    white-space: -moz-pre-wrap;
		    white-space: -pre-wrap;
		    white-space: -o-pre-wrap; 
		    word-wrap: break-word;
		    font-family: GillSans, Calibri, Trebuchet, sans-serif;
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
				$con = new mysqli("127.0.0.1","Noor","Noor","network");
				if($con == null){
					die("Sorry");
				}else{
					//query fro getting user's post 
					$treatedPostId=$con->real_escape_string($_GET["hiddenPostId"]);
					$queryResult = $con->query("SELECT * FROM posts WHERE postId=".$treatedPostId);
					$queryResult->data_seek(0);
					$row=$queryResult->fetch_array(MYSQLI_ASSOC);

					//query for getting likes and comments numbers
					$likeNumbers=0;
					$commentsNumber=0;

					$likeResult=$con->query("SELECT COUNT(*) FROM post_likes WHERE postId=".$row['postId']);
					$likeResult->data_seek(0);
					$likeRow=$likeResult->fetch_array(MYSQLI_NUM);
					$likeNumbers=$likeRow[0];
					$likeResult->close();

					$commentResult=$con->query("SELECT COUNT(*) FROM post_comments WHERE postId=".$row['postId']);
					$commentResult->data_seek(0);
					$commentRow=$commentResult->fetch_array(MYSQLI_NUM);
					$commentsNumber=$commentRow[0];
					$commentResult->close();
					
					//query for getting name
					$nameResult=$con->query("SELECT * FROM users WHERE userId=".$row['userId']);
					//if($nameResult->num_rows > 0){
						$nameResult->data_seek(0);
						$nameRow=$nameResult->fetch_array(MYSQLI_ASSOC);
						$name=$nameRow['firstName']." ".$nameRow['lastName'];
					//}

					// checking if already liked
					$likeCheck=$con->query("SELECT * FROM post_likes WHERE likerId=".$_SESSION['userId']." AND postId=".$row['postId']);
					$likeCheckRow=$likeCheck->fetch_array(MYSQLI_NUM);
					if($likeCheck->num_rows==1)
						$chkd='chkd';
					else
						$chkd='unchkd';

					echo "<div class='col s8 push-s2 posts'>";
					if($row['userId']==$_SESSION['userId'])
							echo "<form>
								<i title='Delete Post' onclick='deletePost(this)' class='material-icons md-18 delete'>delete</i>
								<input type='hidden' name='hiddenPostId' value='".$row['postId']."' />
							</form>
							<img src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."' class='userImg'>";
					else{
						$dpRes=$con->query("SELECT * FROM users WHERE userId=".$row['userId']);
						$dpRow=$dpRes->fetch_array(MYSQLI_ASSOC);
						if($dpRow['dpId']!=NULL){
							$dpPicRes=$con->query("SELECT * FROM picture WHERE pictureId=".$dpRow['dpId']." AND userId=".$dpRow['userId']);
	
							if($dpPicRes->num_rows >= 1){
								$dpPicRes->data_seek(0);
								$dpPicRow=$dpPicRes->fetch_array(MYSQLI_ASSOC);
	
								echo"<img src='data:".$dpPicRow['type'].";base64,".base64_encode($dpPicRow['picture'])."' class='userImg'>";
							}
						}	
						else{
							if($dpRow['gender']==1)
								echo"<img src='default/male.png' class='userImg'>";
							else
								echo"<img src='default/female.png' class='userImg'>";
						}

						$dpRes->close();
					}
						echo "<p class='nameSpanPost'>
								<a href='profile.php?personId=".$row['userId']."'>".$name."</a><br>
								<span class='dateTime'>".$row['timeStamp']."</span>
							</p>
							<div class='col s12 posts'>
								<pre class='stsMsg'>".$row['postMessage']."</pre>
							</div>
							<div class='col s11'>
							<form action='postComment.php' method='post'>
								<i onclick='likeIt(this)' class='material-icons md-24 ".$chkd."'>❤</i>
								<span class='number' title='Show likes' onclick='showLikes(this)'>".$likeNumbers." likes</span>
									<i class='material-icons md-24 cmnt'>✐</i>
									<input type='hidden' name='hiddenPostId' value='".$row['postId']."'/>
								<span id='comNumber1'>".$commentsNumber." comments</span>
								</div>
								<div class='col s12' id='commentBar'>
									<input style='border-color: #40c4ff;' type='text' name='commentField' placeholder='Post A Comment' />
								</div>
							</form>";
						//</div>";
					}

				//displaying comments
				$comResult = $con->query("SELECT * FROM post_comments WHERE postId=".$_GET['hiddenPostId']);
				

				for($a = 0 ; $a < $comResult->num_rows ; $a++){
					$comResult->data_seek($a);
					$comRow=$comResult->fetch_array(MYSQLI_ASSOC);
					$commentorResult=$con->query("SELECT firstName,lastName,dpId,userId,gender FROM users WHERE userId=".$comRow['commentorId']);


					$commentorResult->data_seek(0);
					$commentorRow=$commentorResult->fetch_array(MYSQLI_ASSOC);
					echo "<div class='col s12 comment'>
							<div class='col s12'>
								<form>
									<i title='Delete Comment' onclick='deleteComment(this)' class='material-icons delete-comment'>delete</i>
									<input type='hidden' name='hiddenCommentId' value='".$comRow['postCommentId']."' />
									<input type='hidden' name='hiddenPostId' value='".$row['postId']."' />
								</form>";
					
					$dpRes=$con->query("SELECT * FROM picture WHERE userId=".$comRow['commentorId']);
					
					if($dpRes->num_rows > 0){
						$dpRes->data_seek(0);
						$dpRow=$dpRes->fetch_array(MYSQLI_ASSOC);
						echo "<img src='data:".$dpRow['type'].";base64,".base64_encode($dpRow['picture'])."' class='userImg'>";
					}else{
						if($commentorRow['gender'] == 'male')
							echo "<img src='default/male.png' class='userImg'>";
						else
							echo "<img src='default/female.png' class='userImg'>";
					}
								
					echo"<a href=profile.php?personId=".$commentorRow['userId']." class='commentorName'><b>".$commentorRow['firstName']." ".$commentorRow['lastName']."</b></a><br><pre id='commentPre'>".$comRow['commentMessage']."</pre>
						</div>
					</div>";
						$dpRes->close();
						$commentorResult->close();
				}

				echo "</div>";
			?>
		</div>
	</div>

	<script type="text/javascript" src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
</body>
</html>