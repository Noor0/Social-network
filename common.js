function likeIt(heart){
	//alert(heart.nextSibling.innerHTML);
	var id=heart.parentNode.elements['hiddenPostId'].value;
	var request = new XMLHttpRequest();
	var param="postId="+id;
	var number=heart.parentNode.getElementsByClassName('number')
	request.open('POST','like.php',true);
	request.onreadystatechange=function(){
		console.log(request.responseText);
		if(request.readyState == 4 && request.status == 200){
		//if a person is not friend and likes on post are 0 then  0 is being returned but there is no alert because of the condition fix it later
			if(request.responseText > 0 && request.responseText != 'fault'){
				if(request.responseText.slice(0,1)=="n"){
					heart.className='material-icons md-24 unchkd';
					alert("You're not freinds with the person send a request to get connected ;)");
				}
				else
					heart.className='material-icons md-24 chkd';
				number[0].innerHTML=request.responseText+" likes";
			}else{
				heart.className='material-icons md-24 unchkd';
				var rspns=request.responseText;
				rspns=rspns.slice(1,rspns.length);
				console.log(rspns);
				number[0].innerHTML=rspns+" likes";
			}
		}
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//necessary header for post requests
	request.send(param);

}


function deletePost(bin){
	var id=bin.parentNode.elements['hiddenPostId'].value;
	var request = new XMLHttpRequest();
	request.open("POST","delete.php",true);
	request.onreadystatechange= function(){
		if(request.readyState==4 && request.status==200){
			if(request.responseText=='d'){
				console.log(request.responseText);
				bin.parentNode.parentNode.style.display="none";
			}
			else{
				console.log(request.responseText);
			}
		}
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("postId="+id);
}

function deleteComment(bin){
	var id=bin.parentNode.elements['hiddenCommentId'].value;
	var pid=bin.parentNode.elements['hiddenPostId'].value;
	var request = new XMLHttpRequest();
	request.open("POST","deleteComment.php",true);
	request.onreadystatechange= function(){
		if(request.readyState==4 && request.status==200){
			if(request.responseText.slice(0,1)=='d'){
				console.log(request.responseText);
				bin.parentNode.parentNode.parentNode.style.display="none";
				document.getElementById('comNumber1').innerHTML=request.responseText.slice(1,request.responseText.length)+" comments";
			}
			else{
				console.log(request.responseText);
			}
		}
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("commentId="+id+"&postId="+pid);

}

function logout(a){
	a.parentNode.submit();
}

function home(){
	window.location="home.php";
}

function messages(){
	window.location="messages.php";
}

function profile(){
	window.location="profile.php?personId="+document.getElementById('idd').value;
}

function goToComments(pen){
	var id=pen.parentNode.submit();
	console.log(pen.parentNode.nodeName);
}

function sendRequest(but){
	but.parentNode.submit();
}

function showMe(hoot){
	hoot.parentNode.submit();
}


function requestPage(){
	window.location="requests.php";
}

function accept(yes){
	var id=yes.parentNode.parentNode.elements['chillz'].value;
	var request=new XMLHttpRequest();
	request.open("POST","accept.php",true);
	request.onload=function(){
		if(request.responseText=='done')
			yes.parentNode.parentNode.innerHTML="You are now friends";
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("senderId="+id);
}

function addPostImage(postPic){
	postPic.parentNode.parentNode.elements['pImage'].click();
}

/*function unfriend(uf){
	var id=uf.parentNode.elements['unfriendId'].value;
	var request=new XMLHttpRequest();
	request.open("POST","unfriend.php",true);
	request.onload=function(){
		if(request.responseText=='done'){
			uf.parentNode.parentNode.parentNode.style.display="none";
		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("unfriendId="+id);
}*/

function unfriend(uf){
	var id=uf.parentNode.submit();
}

function ignore(no){
	var id=no.parentNode.parentNode.elements['chillz'].value;
	var request=new XMLHttpRequest();
	request.open("POST","ignore.php",true);
	request.onload=function(){
		if(request.responseText=='done')
			no.parentNode.parentNode.style.display="none";
	};
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("senderId="+id);
}

function change(ch) {
	var file=document.getElementById('picpic');
	file.click();
	document.getElementById('chbut').style.display="block";

}

function showLikes(ll){
	window.location="showLikes.php?postId="+ll.parentNode.elements['hiddenPostId'].value;
}

function makeProPic(mkpp){
	var id=mkpp.parentNode.elements['pictureId'].value;
	var request=new XMLHttpRequest();
	request.open("POST","pictureAction.php",true);
	request.onload=function(){
		if(request.responseText=='done1'){
			
		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("pictureId="+id+"&code=1");
}

function deletePic(delP){
	var id=delP.parentNode.elements['pictureId'].value;
	var request=new XMLHttpRequest();
	request.open("POST","pictureAction.php",true);
	request.onload=function(){
		if(request.responseText=='done2'){

			delP.parentNode.parentNode.parentNode.style.display="none";
		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("pictureId="+id+"&code=2");

}