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

	.ffr{
		float:left;
		width:80px;
	}
	
	#postForm{
		background-color:white;
		margin:auto;
		clear:both;
	}

	#postArea{
		margin-top:20px;
		resize:none;
		border-right:0px solid white;
		border-left:0px solid white;
		outline:none;
		font-size:18px;
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
			<div class="col l8 s12 m12 push-l2" id="postForm">
				<form action="post.php" method="POST">
					<textarea style='border-color: #40c4ff;' class="materialize-textarea inColor" id="postArea" name="status" placeholder="Post a status"></textarea>
					<div class="col s12 m6 l6">
						<a onclick='addPostImage(this)' class='#00c853 green accent-4 waves-effect waves-light btn full'>
							<i class='material-icons right'>photo</i>Add an image
						</a>
						<input type="file" name="pImage" style="display:none;" />
				    </div>
					<div class="col s12 m6 l6 " >
						<button class="#40c4ff light-blue accent-2 btn waves-effect waves-light full" type="submit" name="action">Post</button>
					</div>
				</form>
			</div>
		</div>	
			<!--a qurey will be executed to get the posts and those posts will be displayed through a loop-->
		<div class="row">
			<?php
				global $name;
				$con=new mysqli("127.0.0.1","Noor","Noor","network");
				$result=$con->query("SELECT * FROM posts ORDER BY timeStamp DESC");
				for($a=0 ; $a < $result->num_rows ; $a++){
					$result->data_seek($a);
					$row=$result->fetch_array(MYSQLI_ASSOC);
					$nameResult=$con->query("SELECT * FROM users WHERE userId=".$row['userId']);
					
					if($nameResult->num_rows > 0){
						$nameResult->data_seek(0);
						$nameRow=$nameResult->fetch_array(MYSQLI_ASSOC);
						
						$name=$nameRow['firstName']." ".$nameRow['lastName'];
					}

					//checking if friends
					$friendChk=$con->query("SELECT * FROM friend_list WHERE (userId=".$row['userId']." AND friendId=".$_SESSION['userId'].") OR (userId=".$_SESSION['userId']." AND friendId=".$row['userId'].")");

					if($friendChk->num_rows>=1 || $_SESSION['userId'] == $row['userId']){
						$friends=true;

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

						// checking if already liked
						$likeCheck=$con->query("SELECT * FROM post_likes WHERE likerId=".$_SESSION['userId']." AND postId=".$row['postId']);
						if($likeCheck->num_rows==1)
							$chkd='chkd';
						else
							$chkd='unchkd';


						echo "<div class='card-panel col l8 s12 m12 push-l2 posts'>";
						if($row['userId']==$_SESSION['userId']){
							echo "<form>
								<i title='Delete Post' onclick='deletePost(this)' class='material-icons md-18 delete'>delete</i>
								<input type='hidden' name='hiddenPostId' value='".$row['postId']."' />
							</form>";
							if($_SESSION['dpType']!="no")
								echo "<img src='data:".$_SESSION['dpType'].";base64,".base64_encode($_SESSION['dp'])."' class='userImg'>";
							else{
								if($nameRow['gender']==1)
									echo "<img src='default/male.png' class='userImg'>";
								else
									echo "<img src='default/female.png' class='userImg'>";
							}
						}
						
						else{
							//there was data_seek() bug here
							$dpUserRes=$con->query("SELECT * FROM users WHERE userId=".$row['userId']);
							$dpUserRes->data_seek(0);
							$dpUserRow=$dpUserRes->fetch_array(MYSQLI_ASSOC);
							if($dpUserRow['dpId']!=NULL){
								$dpRes=$con->query("SELECT * FROM picture WHERE pictureId=".$dpUserRow['dpId']);
								if($dpRes->num_rows > 0){
									$dpRes->data_seek(0);
									$dpRow=$dpRes->fetch_array(MYSQLI_ASSOC);
									//$dpRes->close();
									//$dpUserRes->close();
									echo" <img src='data:".$dpRow['type'].";base64,".base64_encode($dpRow['picture'])."' class='userImg'>";
								}
							}

							else{
								if($nameRow['gender']==1)
									echo "<img src='default/male.png' class='userImg'>";
								else
									echo "<img src='default/female.png' class='userImg'>";
							}
							
						}
							
						echo"
								<a class='nameSpanPost' href='profile.php?personId=".$row['userId']."'>".$name."</a>
								<span class='dateTime' >".$row['timeStamp']."</span>
							
							<div class='col s12 posts'>
								<pre class='stsMsg'>".$row['postMessage']."</pre>
							</div>
							<div class='col s12'>
								<form action='comment.php' method='get'>
									<input type='hidden' name='hiddenPostId' value='".$row['postId']."' />
									<i onclick='likeIt(this)' class='material-icons md-24 ".$chkd."'>❤</i>
									<span class='number' title='Show likes' onclick='showLikes(this)'>".$likeNumbers." likes</span >
									<i onclick='goToComments(this)' class='material-icons md-24 cmnt'>✐</i>
										<span>".$commentsNumber." comments</span>
								</form>
							</div>
							</div>";
					
						}
					}
				$result->close();
				$con->close();
			?>
		</div>
	
	<script src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>	
</body>
</html>