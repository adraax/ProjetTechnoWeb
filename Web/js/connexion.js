$(function() {
	//Chargement de la fenêtre modale
	$('#mod').load('/connectionajax', function(data) {
		console.log("Fenêtre de connexion modale chargée !");
	});
	
	//gestionnaire de clic sur le bouton du formulaire de la fenêtre modale
	$('#myModal').on('click', '#connexion', function(event){
		event.preventDefault();
		var username = $('#username').val();
		var password = $('#password').val();
		console.log("connexion");
		//envoi de la requete post
		$.post('/connectionajax', {username: username, password: password}, function(data) {
			if(data=="ok")
			{
				$('#myModal').modal('toggle');
				$('#navbar').load('/navbar');
			}
			else
			{
				//rafraichissement du contenu de la fenetre modale
				$('#mod').html(data);	
			}
		});
	});
});