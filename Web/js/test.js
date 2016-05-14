$(function() {
	$('#mod').load('/connectionajax', function() {
		console.log("Fenêtre de connexion modale chargée !");
	});
	
	$('#myModal').on('click', 'button', function(event){
		event.preventDefault();
		var username = $('#username').val();
		var password = $('#password').val();
		console.log("prevent default modal "+username+" "+password);
		console.log("content : "+$('#content').val());
		$('#mod').load('/connectionajax', {username: username, password: password}, function() {
			console.log('connexion');
		});
	});
});