<!DOCTYPE html>
<html>
<head>
	<title>signup</title>
	<!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<html>
<body>

<?php

$con=new mysqli("127.0.0.1","Noor","Noor","network") ;

if($con->connect_error){
	die("could create your account");
}



$statementUser = $con->prepare("INSERT INTO users VALUE(?,?,?,?,?,?,?,?,?)");
$statementPassword = $con->prepare("INSERT INTO password VALUE(?,?)");

$id=NULL;
$email="";
$firstName="";
$lastName="";
$country="";
$city="";
$phone=0;
$gender=NULL;

$password="";
$dpId=NULL;

$statementUser->bind_param('isssssiii', $id, $email, $firstName, $lastName, $country, $city, $phone, $gender,$dpId);

//for id of user generated and stored in db
$dbUserId="";

//$userNameQuery=$con->prepare("SELECT firstName lastName FROM users WHERE userId= ?");

try{
	if(isset($_POST)){
		if(isset($_POST['sign'])){
			if(!empty($_POST['FirstName'])){
				if(checkOnlyString($con->real_escape_string($_POST['FirstName']))){
					$firstName=$con->real_escape_string($_POST['FirstName']);
				}else {throw new Exception("Enter valid first name");}
			}else {throw new Exception("First name is mandatory!!!");}

			if(!empty($_POST['LastName'])){
				if(checkOnlyString($con->real_escape_string($_POST['LastName']))){
					$lastName=$con->real_escape_string($_POST['LastName']);
				}
				else {throw new Exception("Enter valid last name");}
			}
			else {throw new Exception("Last name is mandatory!!!");}

			if(!empty($_POST['Email'])){
				if(preg_match("/(.*)@(.*)\.com/",$_POST['Email'])){
					$email=$con->real_escape_string($_POST['Email']);
				}
				else {throw new Exception("Enter valid Email");}
			}
			else {throw new Exception("Email is mandatory!!!");}

			if(!empty($_POST['Password'])){
				if(preg_match("/^[\S]+$/",$_POST['Password'])){
					$fixedPassword=$con->real_escape_string($_POST['Password']);
					$password=hash('ripemd128',"aboveandbeyond".$fixedPassword);
				}
				else {throw new Exception("pasword cannot contain spaces or tab indentation");}
			}
			else {throw new Exception("Password is mandatory!!!");}

			if(!empty($_POST['Country'])){
				if(checkOnlyString($_POST['Country'])){
					$country=$con->real_escape_string($_POST['Country']);
				}
				else {throw new Exception("Enter valid country name");}
			}
			else {$country=NULL;}

			if(!empty($_POST['City'])){
				if(checkOnlyString($_POST['City'])){
					$city=$con->real_escape_string($_POST['City']);
				}
				else {throw new Exception("Enter valid city name");}
			}
			else {$city=NULL;}

			if(!empty($_POST['Phone'])){
				if(checkOnlyNumber($_POST['Phone'])){
					$phone=$con->real_escape_string($_POST['Phone']);
				}
				else {throw new Exception("Enter valid phone number");}
			}
			else {$phone=NULL;}
			
			if(!empty($_POST['gender'])){
				if($_POST['gender']=="male")$gender=1; else $gender=0;
			}


			$statementUser->execute();
			$genId=$statementUser->insert_id;
			$statementPassword->bind_param('is', $genId, $password);
			$statementPassword->execute();
			echo "<h3>A confirmation email has been sent to you for verification</h3><br>";
			echo "<a href='index.php'>back to main page</a><br>";
		}

		if(isset($_POST['log'])){
			if(!empty($_POST['LogEmail'])){
				if(preg_match("/(.*)@(.*)\.com/",$_POST['LogEmail'])){
					
					$chkdEmail=$con->real_escape_string($_POST['LogEmail']);
					$result=$con->query("SELECT * FROM users WHERE email='".$chkdEmail."'");
					
					if($result->num_rows<=0 || $result->num_rows>=2){
						echo "<br>you entered either a wrong email or password<br><a href='reset.html'>reset your password</a><br>";
						throw new Exception("Users= $result->num_rows");

					}
					else{

						$result->data_seek(0);
						$row = $result->fetch_array(MYSQLI_ASSOC);
						global $dbUserId;
						$dbUserId=$row['userId'];

						if(!empty($_POST['LogPassword'])){
						if(preg_match("/^[\S]+$/",$_POST['LogPassword'])){
							
							$chkdPassword=$con->real_escape_string($_POST['LogPassword']);
							$hashedPassword=hash('ripemd128',"aboveandbeyond".$chkdPassword);
							
							$result=$con->query("SELECT * FROM password WHERE userId='".$dbUserId."'");
							if($result->num_rows!=1)
								throw new Exception("more than 1 pass");
							else{
								$result->data_seek(0);
								$row=$result->fetch_array(MYSQLI_ASSOC);
								$dbPassword=$row['password'];

								if($dbPassword == $hashedPassword)
									logIn($con);
								else{throw new Exception("Incorrect Password");}
							}

						}
						else {throw new Exception("pasword cannot contain spaces or tab indentation");}
						}
						else {throw new Exception("Password is mandatory!!!");}
					}
				}
				else {throw new Exception("Enter valid Email");}
			}
			else {throw new Exception("enter email to login");}

			
		}
	}
}
catch(Exception $e){
	echo "<a href='shotwah.html'>back to main page</a><br>";
	$con->close();
	die($e->getMessage());
}



function checkOnlyString($strng){
	if(preg_match("/^[A-Za-z]+$/", $strng))
		return true;
	else
		return false;
}

function checkOnlyNumber($strng){
	if(preg_match('/^[0-9]+$/', $strng))
		return true;
	else
		return false;
}

function logIn($con){
	global $dbUserId,$userNameQuery;
	session_start();
	$_SESSION['userId']=$dbUserId;
	echo "welcome ".$dbUserId;
	$result=$con->query("SELECT * FROM users WHERE userId='".$dbUserId."'");
	
	if($result != NULL){
		$result->data_seek(0);
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$_SESSION['userName']=$row['firstName']." ".$row['lastName'];
		echo "welcome ".$_SESSION['userName'];
		$dpResult=$con->query("SELECT * FROM picture WHERE pictureId=".$row['dpId']);
		if($dpResult->num_rows <= 0){
			if ($row['gender']==1) {
				$_SESSION['dp']="default/male.png";
			}
			else{
				$_SESSION['dp']="default/female.png";
			}
			$_SESSION['dpType']="no";
		}	
			else{	
				$dpResult->data_seek(0);
				$dpRow=$dpResult->fetch_array();
				$_SESSION['dp']=$dpRow['picture'];
				$_SESSION['dpType']=$dpRow['type'];
			}
			
		}
		$con->close();
	}
	header("Location:home.php");

?>
</body>
</html>