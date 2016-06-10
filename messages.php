<?php
require_once "common.php";
checkSession();
$reqs=requestCheck();
$_SESSION["lastMsgId"]=0;
$_SESSION["currentPerson"]=0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Photos</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
	<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="common.css">
	<style type="text/css">

	body{
		height:100%;
		width:100%;
		position:absolute;
	}
	
	.l-panel{
		height:100%;
		padding: 120px 35px 0px 0px;
	}
	.fullbody{
		position:relative;
		margin:0px;
		height:88%;

	}
	.userImgMsg{
		width:45px;
		height:45px;
		background:red;
		float:left;
	}
	.people{
		border-bottom: 1px solid #bfbfbf;
		height:70px;
		padding-top:20px;
	}
	.people:hover{
		background-color:#bfbfbf;
	}
	
	.they{
		float:left;
		background-color:#00dc64;
		/*#00e600*/
		
	}

	.mine{
		float:right;
		background-color:#00d2e6;

	}
	.message{
		clear:both;
		padding:5px 14px 5px 14px;
		color:white;
		font-family: 'Lato', sans-serif;
		font-size:18px;
		max-width:50%;
		border-radius: 20px;
	}
	.mid{
		border-right: 2px solid #bfbfbf;
		border-left: 2px solid #bfbfbf;
		height:100%;
	}

	#txtArea{
		width:100%;
	}

	#screen{
		height:73%;
		border-bottom: 2px solid #bfbfbf;
		overflow:auto;
		width:100%;
	}

	#nameOnTop{
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
	<!--<div class="container">-->
		<div class="row fullbody">
			<div class="col l3 l-panel">
				<?php
					$con=new mysqli("127.0.0.1","Noor","Noor","network");
					if(!$con)
						die("");
					$result=$con->query("SELECT * FROM friend_list WHERE userId=".$_SESSION['userId']." OR friendId=".$_SESSION['userId']);
					if($result->num_rows > 0){
						for($a=0;$a<$result->num_rows;$a++){
							$result->data_seek($a);
							$row=$result->fetch_array(MYSQLI_ASSOC);
							echo "<div class='col s12 people' onclick='setMe(this);clearScreen();'>";
							//$friendResult=NULL;
							if($row['userId'] == $_SESSION['userId']){
								$friendResult=$con->query("SELECT * FROM users WHERE userId=".$row['friendId']);
							}
							if($row['friendId'] == $_SESSION['userId']){
								$friendResult=$con->query("SELECT * FROM users WHERE userId=".$row['userId']);
							}
							
							if($friendResult->num_rows > 0){
								$friendResult->data_seek(0);
								$friendRow=$friendResult->fetch_array(MYSQLI_ASSOC);
								if($friendRow['dpId'] != NULL){
									$friendDpResult=$con->query("SELECT * FROM picture WHERE userId=".$friendRow['userId']." AND pictureId=".$friendRow['dpId']);
									echo "<img src='data:".$friendRow['type'].";base64,".base64_encode($friendRow['picture'])."' class='userImgMsg'>";
								}
								else{
									if($friendRow['gender']==1)
										echo "<img src=default/male.png class='userImgMsg'>";
									else
										echo "<img src=default/female.png class='userImgMsg'>";
								}
								echo "<a href='profile.php?personId='".$friendRow['userId']."' class='nameSpanPost'>".$friendRow['firstName']." ".$friendRow['lastName']."</a>";
							}

							echo"<input type='hidden' class='thePerson' value='".$friendRow['userId']."' />
								<!--<input type='hidden' class='lastUpperMsg' value='-1' /> for lazy loading of messages -->
							</div>";
						}
					}
				?>
			</div>
			<div class="col l7 m8 s8 mid">
			<div class="col s12"><!--<div class="col s4 push-s5">--><h5 id="nameOnTop">Noor Haq</h5></div><!--</div>-->
				<div id="screen">
					<p class="mine message">hello this is your father fatherfatherfather father father father father father father father father father father father father </p>
					<p class="they message">abcd father father father father father father father father father father father father </p>
					<p class="they message">abcd father father father father father father father father father father father father </p>
				</div>
				<div class="input-field col s12">
		          	<textarea id="txtArea" name="message" class="materialize-textarea"></textarea>
		          	<label for="txtArea">Send a message</label>
		        </div>
			</div>
		</div>
		<script type="text/javascript">
			var txtArea=document.getElementById('txtArea');
			
			var screen = document.getElementById('screen');

			var requestObj = new XMLHttpRequest();

			var currentSelectedPerson;

			var nameOnTop = document.getElementById('nameOnTop');

			txtArea.addEventListener("keydown", function(evt){
				if(evt.keyCode == 13){
					//sendMessage
					requestObj.open("POST","messageFunctions.php",true);
					requestObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					requestObj.send("theMessage="+txtArea.value+"&opCode=2");
					requestObj.onload=function() {
						if(requestObj.responseText=="notsent"){
							alert("seems like there is some problem, couldn't send your message");
						}
						else{
							txtArea.value="";
						}
					}

				}
			});
			function setMe(me){
				var thePersonArray=me.getElementsByClassName("thePerson");
				if(currentSelectedPerson != thePersonArray[0].value){
					requestObj.open("POST","messageFunctions.php",true);
					requestObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					var thePersonArray=me.getElementsByClassName("thePerson");
					requestObj.send("person="+thePersonArray[0].value+"&opCode=1");
					thePersonArray=me.getElementsByClassName("nameSpanPost");
					nameOnTop.innerHTML= thePersonArray[0].innerHTML;
					initialFetch();
				}
			}

			function initialFetch(){
				requestObj.open("POST","messageFunctions.php",true);
				requestObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				requestObj.send("opCode=4");
				requestObj.onload=function(){
					if(requestObj.responseText != "nono"){
						var responseJSON = JSON.parse(requestObj.responseText);
						
					}
				}

			}

			function clearScreen(){
				screen.innerHTML = "";
			}

			screen.addEventListener('onscroll', function(evt){
				if(screen.scrollTop == 0){					
					requestObj.open("GET","messageFunctions.php",true);
					requestObj.send();
				}
			});


		</script>	
	<script src='common.js'></script>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
</body>
</html>