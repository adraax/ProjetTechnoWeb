$(function() {
	$('#mod').load('/connectionajax', function() {
		console.log("Fenêtre de connexion modale chargée !");
		
	});	
});

setTimeout(function() {
	$('#myModal button').click(function(event){
			event.preventDefault();
			var username = $('#username').val();
			var password = $('#password').val();
			console.log("prevent default modal "+username+" "+password);
			console.log("content : "+$('#content').val());
			$('#mod').load('/connectionajax', {username: username, password: password}, function() {
				console.log('connexion');
			});
		});
}, 1000);

var listener = function() {
	$('#myModal button').click(function(event){
			event.preventDefault();
			var username = $('#username').val();
			var password = $('#password').val();
			console.log("prevent default modal "+username+" "+password);
			console.log("content : "+$('#content').val());
			$('#mod').load('/connectionajax', {username: username, password: password}, function() {
				console.log('connexion');
			});
		});
		setTimeout(listener(), 500);
};

setTimeout(listener(), 1000);