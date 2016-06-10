<?php
require_once "common.php";
/*if(!isset($_SESSION['userId'])){
  header("Location:home.php");
}*/

?>
<!DOCTYPE html>
<html>
<head>
	<title>Social Network</title>
	<!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.css">
  <link rel="stylesheet" href="common.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style type="text/css">
    
  	body{
  		position: absolute;
  		height: 100%;
  		width: 100%;
  	}

    html {
    	font-family: GillSans, Calibri, Trebuchet, sans-serif;
    }

    h5{
   		text-align: center;
    }

  	#bodiv{
  		position: absolute;
  		height:100%;
  		width: 100%;
  		margin: auto;
  		color: #2c3e50;

  	}

  	#Header{
  		margin: 0px;
  	}

  	#HeaderText{
  		text-align: center;
  		width: 100%;
  	}
  	#panel{
  		text-align: center;
  		margin-top: 50px;
  	}

  	#form{
  		position: relative;
  		width: 50%;
  		margin: auto;
  		margin-top: 10px;
  		height: auto;
  	}

  	.panel{
  		float: left;
  		display:inline-block;
  		width: 50%;
  		background-color: white;
  		margin: auto;
  		overflow: hidden;
  		height: 50px;
  		border-bottom:2px solid #1abc9c;
  		cursor:pointer;
  		-webkit-transition: height .3s ease;
  		-moz-transition: height .3s ease;
  		padding-bottom: 10px;
  	}

  	.panel:hover{
  		position:relative;
  		height:540px;

  	}

  	footer{ 
  		position: absolute;
  		height: 30px;
  		width: 99%;
  		bottom: .7%;
  		left: .5%;
  		margin: 0px;
  		padding: 0px;
  		float: left;	
  	}

  .name{
  	float: left;
  	color: #2c3e50;
  }

  .icons{
  	float:right;
  }

  img{
  	width: 30px;
  	height: 30px;
  }

  .incol{
    border-color: #00c853;
  }
  </style>

</head>
<body>
	<div id="bodiv">

		<div id="Header" class="row">
			<div class="col s12 l12">
				<h2 id="HeaderText">Social Network</h2>
			</div>
		</div>

				
		<div id="form">
			<div id="loginForm"  class="panel">
				<h5>Log In</h5>
				<form action = "signup.php" method = "POST">
					<input type="text" name="LogEmail" placeholder="Email" style='border-color: #40c4ff;'></input>
					<input type="password" name="LogPassword" placeholder="password" style='border-color: #40c4ff;'></input>

					<input type="submit" value="LogIn" name="log" id="loginbtn" class="#40c4ff light-blue accent-2 waves-light btn"><!--<i class="material-icons left">vpn_key</i>--></input>
				</form>
			</div>

			<div id="signupForm" class="panel">
				<h5>Signup</h5>
				<form action = "signup.php" method = "POST">
					<input type="text" name="FirstName" placeholder="First Name" style='border-color: #40c4ff;'/>
					<input type="text" name="LastName" placeholder="Last Name" style='border-color: #40c4ff;'/>
					<input type="text" name="Email" placeholder="Email" style='border-color: #40c4ff;'/>
					<input type="password" name="Password" placeholder="password" style='border-color: #40c4ff;'/>
					<input type="text" name="Country" placeholder="Country" style='border-color: #40c4ff;'/>
					<input type="text" name="City" placeholder="City" style='border-color: #40c4ff;'/>
					<input type="text" name="Phone" placeholder="Phone" style='border-color: #40c4ff;'/>
				      <input name="gender" type="radio" id="maleRadio" value="male" style='color: #40c4ff;' />
				      <label for="maleRadio">Male</label>
				      <input name="gender" type="radio" id="femaleRadio" value="female"/>
				      <label for="femaleRadio">Female</label><br>
					<input type="submit" value="SignUp" name="sign" id="signupbtn" class="#40c4ff light-blue accent-2 waves-effect waves-light btn"><!--<i class="material-icons left">perm_identity</i>--></input>
				</form>
			</div>
		</div>

	</div>

	
	<footer>
		<span class="name">Noor Ul Haq</span>
		<span class="icons">
			<a href="https://www.facebook.com/Trance303" target="_blank"><img src="fb.png"></a>
			<a href="https://soundcloud.com/noor-khan-4/tracks" target="_blank"><img src="sd.png"></a>
			<a><img src="tw.png"></a>
		</span>
	</footer>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.js"></script>
	
</body>
</html>