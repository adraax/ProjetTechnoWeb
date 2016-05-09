//Fonction d'Instance
function getXMLHttpRequest()
{
    var xhr = null;
  
    if (window.XMLHttpRequest || window.ActiveXObject)
	{
        if(window.ActiveXObject)
		{
            try
			{
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e)
			{
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
        else
		{
            xhr = new XMLHttpRequest();
        }
    }
    else
	{
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return null;
    }
    return xhr;
}

//Envoi de la requete
function modiftransport(id_competiteur, id_competition) {
	var xhr   = getXMLHttpRequest();

	xhr.onreadystatechange = function() { 
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
			modif_bouton_transport(xhr.responseXML);
	}
	
	xhr.open("POST", "modiftransport", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("id_competiteur=" + id_competiteur + "&id_competition=" + id_competition);
}

//Lecture de la réponse
function modif_bouton_transport(oData) {
	var nodes   = oData.getElementsByTagName("Reponse");
	if(nodes[0].getAttribute("name")=='true')
		document.getElementById("bouton_transport").innerHTML="S'inscrire au transport";
	else
		if(nodes[0].getAttribute("name")=='false')
			document.getElementById("bouton_transport").innerHTML="Annuler l'inscription au transport";
		else
			document.getElementById("bouton_transport").innerHTML="Plus de place disponible !";
}