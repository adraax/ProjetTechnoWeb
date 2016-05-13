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
function request(oSelect) {
	var value = oSelect.options[oSelect.selectedIndex].value;
	var xhr   = getXMLHttpRequest();

	xhr.onreadystatechange = function() { 
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
			readData(xhr.responseXML);
	}
	
	xhr.open("POST", "returncategorie", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("num=" + value);
}

//Lecture de la réponse
function readData(oData) {
	var nodes   = oData.getElementsByTagName("categorie");
	for (var i=0, c=nodes.length; i<c; i++) 
		document.getElementById(nodes[i].getAttribute("name")).checked=true;
}