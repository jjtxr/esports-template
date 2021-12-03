$(".message").keypress(function(e){
	if(e.keyCode == 13){
		envoi();
	}
});
$(".messagePrivate").keypress(function(e){
	if(e.keyCode == 13){
		envoiPrivate();
		return false;
	}
});
$("#sendPrivate").click(function(){
	envoiPrivate();
	return false;
});

function allMsg(){
	$("#black").height($(document).height());
	var top = $(window).height()/2 - $("#shoutAll").height()/2;
	var left = $(window).width()/2 - $("#shoutAll").width()/2;
	$("#shoutAll").css({'top':top,'left':left});
	$("#black").fadeToggle();
	$("#shoutAll").fadeToggle();
}
function allMsgClose(){
	$("#black").fadeToggle();
	$("#shoutAll").fadeToggle();
}
function mouseOver(id){
	$("#"+id).css({'background':'#F5F5F5'});
}
function mouseOut(id){
	$("#"+id).css({'background-color':'#fff'});
}
//private
function private(guess,user){
	popupcentree('shout_popup.php?guess='+guess+'&user='+user+'','550','300');
}
//del all private
function delPrivate(user,guess){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=delPrivate&user="+user+"&guess="+guess, 
		success: function(msg){ 
			
		} 
	});
}
//message private del
function delMsgPrivate(id){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=delMsgPrivate&id="+id, 
		success: function(msg){ 
			//$("Tchat").html(msg); 
		} 
	});
}
//message del
function delMessage(){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=del", 
		success: function(msg){ 
			$("Tchat").html(msg); 
		} 
	});
}
//message del 1by1
function delMsg(id){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=delMsg&id="+id, 
		success: function(msg){ 
			$("Tchat").html(msg); 
		} 
	});
}

function refreshTchatPrivate(guess,user){
	$.ajax({ 
		type: "GET",
		url: "some.php",
		data: "action=refreshPrivate&guess="+guess+"&user="+user, 
		success: function(msg){ 
		$("#TchatPrivate").html(msg);
			//document.getElementById("TchatPrivate").innerHTML = msg; 
		} 
	});
	ref();
}

$("#post").submit(function(){
	$("#send").html('Wait...');
	$.ajax({
		type: "POST",
		url : "some.php", //url page
		data: {'action':'envoi','channelID': $('#channelID').val(),'type': $('#type').val(),'message': $('#message').val(),'pseudo': $('#pseudo').val()}, //params
		success:function(html){
			document.getElementById("send").disabled = false;
			document.getElementById("message").focus();
			$("#send").html('envoyer');
			$("#message").val('');
		}
	});
	return false; 
});

function envoiPrivate(){
	$("#sendPrivate").html('Wait...');
	$.ajax({
		type: "POST",
		url : "some.php", //url page
		data: {'action':'envoiPrivate','message': $('#message').val(),'pseudoPrivate': $('#pseudoPrivate').val(),'guessPrivate': $('#guessPrivate').val()}, //params
		success:function(html){
			document.getElementById("sendPrivate").disabled = false;
			document.getElementById("message").focus();
			$("#sendPrivate").html('envoyer');
			$("#message").val('');
		}
	});
	return false; 
}

/*
$("#post").submit(function(){
	var message = document.getElementById("message").value;
	var pseudo = document.getElementById("pseudo").value;
	document.getElementById("send").disabled = true;
	$("#send").html('Wait...');
	$.ajax({ 
		type: "GET", 
		url: "some.php", 
		data: "action=envoi&message="+message+"&pseudo="+pseudo, 
		success: function(msg){ 
		document.getElementById("send").disabled = false;
		document.getElementById("message").focus();
		$("#send").html('envoyer');
			$("#message").val('');
		} 
	});
	return false;
});
*/
/*
function envoi(){
	var message = document.getElementById("message").value;
	var pseudo = document.getElementById("pseudo").value;
	document.getElementById("send").disabled = true;
	$("#sender").html('Wait...');
	$.ajax({ 
		type: "GET", 
		url: "some.php", 
		data: "action=envoi&message="+message+"&pseudo="+pseudo, 
		success: function(msg){ 
		document.getElementById("send").disabled = false;
		document.getElementById("message").focus();
		$("#sender").html('envoyer');
			$("#message").val('');
		} 
	});
}
*/

/*
function envoiPrivate(){
	var message = document.getElementById("message").value;
	var pseudoPrivate = document.getElementById("pseudoPrivate").value;
	var guessPrivate = document.getElementById("guessPrivate").value;
	document.getElementById("sendPrivate").disabled = true;
	$("#sendPrivate").val('Wait...');
	$.ajax({ 
		type: "GET", 
		url: "some.php", 
		data: "action=envoiPrivate&message="+message+"&pseudoPrivate="+pseudoPrivate+"&guessPrivate="+guessPrivate, 
		success: function(msg){ 
		document.getElementById("sendPrivate").disabled = false;
		document.getElementById("message").focus();
		$("#sendPrivate").val('envoyer');
			$("#message").val('');
		} 
	});
}
*/
//Poppup
function popupcentree(page,largeur,hauteur,options) {
	var top=(screen.height-hauteur)/2;
	var left=(screen.width-largeur)/2;
	window.open(page,"","top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+options); 
} 