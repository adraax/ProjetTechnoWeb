$(function() {
	$('#mod').load('/connectionajax', function(data) {
		console.log("Fenêtre de connexion modale chargée !");
	});
	
	$('#myModal').on('click', '#connexion', function(event){
		event.preventDefault();
		var username = $('#username').val();
		var password = $('#password').val();
		console.log("connexion");
		
		$.post('/connectionajax', {username: username, password: password}, function(data) {
			$('#mod').html(data);
		});
		
		/*$('#mod').load('/connectionajax', {username: username, password: password}, function() {
			console.log('connexion');
		});*/
	});
});