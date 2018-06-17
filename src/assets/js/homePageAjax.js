function toggleLoginMessage(){
	if($("#loginMessage").hasClass("hidden")){
		$("#loginMessage").removeClass("hidden");
		$("#loginMessage").html = "<h1>Logging In...</h1>";
	}else{
		$("#loginMessage").addClass("hidden");
		$("#loginMessage").html = '';
	}
}
function toggleCreateMessage(){
	if($("#createMessage").hasClass("hidden")){
		$("#createMessage").removeClass("hidden");
	}else{
		$("#createMessage").addClass("hidden");
	}
}

var SHA512 = new Hashes.SHA512

$(document).ready(function() {
	var notifications = $("#notificationArea");
	
	$("#createForm").on('submit', function(e) {
		e.preventDefault(); // prevent default
		toggleCreateMessage();
		
		var createEmail = $("#createEmail").val();
		var createUserN = $("#createUsername").val();
		var createPassW = SHA512.hex($("#createPassword").val());
		
		if ($("#createPassword").val().length > 5) {
			if ($("#createUsername").val().length < 50) {
				var createStr = "createEmail=" + createEmail + "&createUsername=" + createUserN + "&createPassword=" + createPassW;
			
				$.ajax({
					url: 'assets/php/scripts/create.php', // This script handles the form
					type: 'POST', // Method used to send data !DONT CHANGE
					dataType: 'html', // Request type
					data: createStr, // 'serialize' form data 
					beforeSend: function() {
						$("#createButton").prop('value', 'Creating'); // Indicate that the form is being processed
					},
					success: function(data) {
						toggleCreateMessage();
						notifications.addClass("hasNotification");
						notifications.html(data).fadeIn(1000);
						$("#createButton").prop('value', 'Create');
					},
					error: function(e) {
						toggleCreateMessage();
						console.log(e); // Log any errors
					}
				});
			} else {
				alert("Username too long");
				toggleCreateMessage();
			}
		} else {
			alert("Password not long enough");
			toggleCreateMessage();
		}
	});
	$("#loginForm").on('submit', function(e) {
		e.preventDefault(); // prevent default
		toggleLoginMessage();
		
		var loginID = $("#loginIdentity").val();
		var loginPW = SHA512.hex($("#loginPassword").val());
		var loginStr = "loginIdentity=" + loginID + "&loginPassword=" + loginPW;
		
		$.ajax({
			url: 'assets/php/scripts/login.php', // This script handles the form
			type: 'POST', // Method used to send data !DONT CHANGE
			dataType: 'html', // Request type
			data: loginStr, // 'serialize' form data 
			beforeSend: function() {
				$("#loginbutton").prop('value', 'Logging In'); // Indicate that the form is being processed
			},
			success: function(data) {
				toggleLoginMessage();
				notifications.addClass("hasNotification");
				notifications.html(data).fadeIn(1000);
				$("#loginbutton").prop('value', 'Login');
			},
			error: function(e) {
				toggleLoginMessage();
				console.log(e); // Log any errors
			}
		});
	});
});