<?php
require_once "common.php";
checkSession();

if(isset($_POST) ){

    if(isset($_POST['opCode'])){
        
        if($_POST['opCode']==1){
            //set current person
            if(!empty($_POST['person'])){
                $con = new mysqli("127.0.0.1","Noor","Noor","network");
                if($con==false)
                    die("no connection");
                $result=$con->query("SELECT * FROM friend_list WHERE (userId=".$_SESSION['userId']." AND friendId=".$_POST['person'].") OR (userId=".$_POST['person']." AND friendId=".$_SESSION['userId'].")");
                if($result->num_rows == 1){
                    $_SESSION["lastLowerMsgId"]=0;
                    $_SESSION["lastUpperMsgId"]=0;
                    $_SESSION['currentPerson']=$_POST['person'];
                    $result->close();
                    $con->close();
                }
            }
        }

        if($_POST['opCode']==2){
            //insert message in database
            $con=new mysqli("127.0.0.1","Noor","Noor","network") ;
            if($con==false)
                die("no connection");
            $prpdStmnt=$con->prepare("INSERT INTO messages VALUES (?,?,?,?)");
            $id=NULL;
            $senderId=$_SESSION['userId'];
            $recieverId=$_SESSION['currentPerson'];

            $message=trim($_POST['theMessage']);
    		$message=$con->real_escape_string($message);
    		$message=$con->real_escape_string($message);
    		$message=htmlentities($message);
            
            $prpdStmnt->bind_param("iisi",$senderId,$recieverId,$message,$id);
            $prpdStmnt->execute();
            if($con->affected_rows > 0){
                echo "sent";
            }
            else{
                echo "notsent";
            }
            $prpdStmnt->close();
            $con->close();
        }

        if ($_POST['opCode']==3) {
            
            $con = new mysqli("127.0.0.1","Noor","Noor","network");
            if($con==false)
                die("no connection");
            
            $scrollResult = $con->query("SELECT * FROM messages WHERE ((senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION["currentPerson"].") OR (senderId=".$_SESSION["currentPerson"]." AND recieverId=".$_SESSION['userId'].")) AND messageId < ".$_SESSION["lastUpperMsgId"]." ORDER BY messageId DESC LIMIT 5");
            
            if($scrollResult->num_rows > 0){
                $jsonStr="{\"messages\":[";

                for ($i=0; $i < $scrollResult->num_rows; $i++) {

                    $scrollResult->data_seek($i);
                    $scrollRow=$scrollResult->fetch_array(MYSQLI_ASSOC);
                    $jsonStr.="{\"id\":\"".$scrollRow['senderId']."\" , \"theMessage\":\"".$scrollRow['message']."\"}";
                    
                    if ($i != $scrollResult->num_rows-1) {
                        $jsonStr.=",";
                    }

                    if($i == $scrollResult->num_rows-1)
                        $_SESSION["lastUpperMsgId"]=$scrollRow['messageId'];
                }

                $jsonStr.="]}";
                echo $jsonStr;
            }
        }

        if($_POST['opCode']==4){
            //initial fetch
            $con = new mysqli("127.0.0.1","Noor","Noor","network");
            if($con==false)
                die("nono");

            $resultInital=$con->query("SELECT * FROM (SELECT * FROM messages WHERE (senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION['currentPerson'].") OR (senderId=".$_SESSION['currentPerson']." AND recieverId=".$_SESSION['userId'].") ORDER BY messageId DESC LIMIT 20)AS newTable ORDER BY messageId ASC");

            $theBulk="";
            if($resultInital->num_rows > 0){

                $theBulk="{\"messages\":[";
                for ($i=0; $i < $resultInital->num_rows; $i++) {
                    $resultInital->data_seek($i);
                    $initalRow=$resultInital->fetch_array(MYSQLI_ASSOC);

                    if ($i==0) 
                        $_SESSION["lastUpperMsgId"]=$initalRow['messageId'];

                    $theBulk.="{\"id\":\"".$initalRow['senderId']."\" , \"theMessage\": \"".$initalRow['message']."\"}";
                    
                    if($i == ($resultInital->num_rows-1))
                        $_SESSION["lastLowerMsgId"]=$initalRow['messageId'];
                    else
                        $theBulk.=",";
                    
                }
                $theBulk.="]}";
                echo $theBulk;
            }
            $resultInital->close();
            $con->close();
            /*else
                echo "nono";*/

            //fetching last messages from database
            /*$resultInital=$con->query("SELECT * FROM (SELECT * FROM messages WHERE (senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION['currentPerson'].") OR (senderId=".$_SESSION['currentPerson']." AND recieverId=".$_SESSION['userId'].") ORDER BY id DESC LIMIT 10)AS new ORDER BY id ASC");
            if($resultInital->num_rows > 0 ){
                for ($i=0; $i < $resultInital->num_rows; $i++) {

                }
            }*/
        }

    }//isset($_POST['opCode'])

}
?>
