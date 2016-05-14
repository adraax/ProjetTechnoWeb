$('#mod').load('/connectionajax', function() {
  console.log("Fenêtre de connexion modale chargée !");
});

$('#connexion').click(function(event){
  event.preventDefault();
  console.log("prevent default");
});