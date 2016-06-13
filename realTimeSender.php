<?php  
session_start();
//server send events file
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');
echo "retry:100\n\n";
//echo "Event: check\n\n";	//event-type = check
$con=new mysqli("localhost","Noor","Noor","network");
$res=$con->query("SELECT * FROM messages WHERE ((senderId=".$_SESSION['userId']." AND recieverId=".$_SESSION["currentPerson"].") OR (senderId=".$_SESSION['currentPerson']." AND recieverId=".$_SESSION["userId"].") )AND messageId > ".$_SESSION["lastLowerMsgId"]);
if($res->num_rows>0)
	$string="data: {\"themMessasges\" : [";
for ($i=0 ; $i < $res->num_rows ; $i++) {
	$res->data_seek($i);
	$row=$res->fetch_array(MYSQLI_ASSOC);
	//$row['names']
	$string.="{ \"id\" : \"{$row['senderId']}\",\"theMessage\":\"{$row['message']}\" }";
	if($i === $res->num_rows-1){
		$_SESSION["lastLowerMsgId"]=$row['messageId'];
	}
	else
		$string.= ",";
}
	
	$string.= "]}";
	//$string=html_entity_decode($string);
	$string.= "\n\n";
	echo $string;
	/*$asd="event:msgchk\n";
	$asd.="data:{$_SESSION["lastLowerMsgId"]}\n\n";*/
	flush();

/*
4 fields of SSE  id,event,retry,data

In SSE server sends data in block of text(also called a message) so a header of "Content-Type:text/event-stream\n\n" is required
In SSE format each block of text is terminated by "\n\n"

by default a SSE server will try to connect after every 3 seconds.

If your message is longer, you can break it up by using multiple "data:" lines. Two or more consecutive lines beginning with "data:" will be treated as a single piece of data, meaning only one message event will be fired. Each line should end in a single "\n" (except for the last, which should end with two). The result passed to your message handler is a single string concatenated by newline characters.

data: first line\n
data: second line\n\n

will produce "first line\nsecond line" in e.data. One could then use e.data.split('\n').join('') to reconstruct the message sans "\n" characters.

Using multiple lines makes it easy to send JSON without breaking syntax:

data: {\n
data: "msg": "hello world",\n
data: "id": 12345\n
data: }\n\n
and possible client-side code to handle that stream:

source.addEventListener('message', function(e) {
  var data = JSON.parse(e.data);
  console.log(data.id, data.msg);
}, false);
-----------------------------------------------------------------------------------------------------------------------------------
You can send a unique id with an stream event by including a line starting with "id:":

id: 12345\n
data: GOOG\n
data: 556\n\n
Setting an ID lets the browser keep track of the last event fired so that if, the connection to the server is dropped, a special HTTP header (Last-Event-ID) is set with the new request. This lets the browser determine which event is appropriate to fire. The message event contains a e.lastEventId property.


Specifying an event name

A single event source can generate different types events by including an event name. If a line beginning with "event:" is present, followed by a unique name for the event, the event is associated with that name. On the client, an event listener can be setup to listen to that particular event.

For example, the following server output sends three types of events, a generic 'message' event, 'userlogon', and 'update' event:

data: {"msg": "First message"}\n\n
event: userlogon\n
data: {"username": "John123"}\n\n
event: update\n
data: {"username": "John123", "emotion": "happy"}\n\n
With event listeners setup on the client:

source.addEventListener('message', function(e) {
  var data = JSON.parse(e.data);
  console.log(data.msg);
}, false);

source.addEventListener('userlogon', function(e) {
  var data = JSON.parse(e.data);
  console.log('User login:' + data.username);
}, false);

source.addEventListener('update', function(e) {
  var data = JSON.parse(e.data);
  console.log(data.username + ' is now ' + data.emotion);
}, false);


*/
?>

