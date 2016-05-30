# Configuration du projet de Technologies du Web

* Il faut tout d'abord créer un virtual-host qui pointe sur le dossier Web/ du projet
* Activer le module *rewrite_module* d'Apache s'il n'est pas activé
* Utiliser le fichier bd.sql pour créer la base de données et la remplir
* Changer l'hote de la bd ainsi que le login/mot de passe dans le fichier *Lib/GJLMFramework/PDOFactory.php*


# Liste des utilisateurs et leur role
* login : admin, password : admin, role = administrateur
* login : entraineur, password : entraineur, role = entraineur
* login : secretaire, password : secretaire, role = secretaire
* login : competiteur, password : competiteur, role = competiteur
* login : competiteur2, password : competiteur2, role = competiteur
