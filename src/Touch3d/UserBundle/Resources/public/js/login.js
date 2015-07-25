var LOADER_GIF = '\"./img/loadinfo.gif\"';
var ConnectAdmin_URL = './controllers/_conxAdmin.php';

$(document).ready(function() {
	$("#login").click(function() {
		$("#loading").fadeIn();
		var memBool = "";
		var remember = document.getElementById('remember');
		if (remember.checked) {
			memBool = "true";
		} else {
			memBool = "false";
		}

		$.post(ConnectAdmin_URL, {
			user : $("#user").val(),
			pass : $("#pass").val(),
			mem : memBool
		}, function(data) {
			if (data=="ok") {
				afficheMess("<p>Success !</p>");
				var timer1 = setInterval("goIndex()", 2000);
			} else if (data=="no") {
				afficheMess("<p>Wrong password or name !</p>");
			} else {
				afficheMess("<p>No data !</p>");
			}

		}).always(function() {
			$("#loading").fadeOut();
		});

	});

});



function afficheMess(s) {
	$('#messageInfo').html(s);
	$('#messageInfo').fadeIn('slow').fadeOut(200).fadeIn(200).fadeOut(8000);
}

function goIndex() {
	document.location.replace("index.php");
}
function sleep(milliseconds) {
	var start = new Date().getTime();
	while ((new Date().getTime() - start) < milliseconds) {
	}
}
$(document).ready(function() {
	$("#messageInfo").hide();
	$("#loading").css( {
		"background" : "url(" + LOADER_GIF + ") no-repeat center transparent",
		"float" : "left",
		"height" : "30px",
		"width" : "30px",
		"padding" : "5px",
		"margin" : "2px"

	}).hide();
	
});
