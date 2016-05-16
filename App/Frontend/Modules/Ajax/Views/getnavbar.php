 <!-- Barre de navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"> Projet TechnoWeb</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/listecompetitions">Comp&eacute;titions</a></li>
                    <?php if(!empty($user->getAttribute('roles')) && in_array('admin', explode(',',$user->getAttribute('roles')))) {?>  
                    <li><a href="/ajoutpersonne">Administration</a></li>  
                    <?php } if(!empty($user->getAttribute('roles')) && in_array('secretaire', explode(',',$user->getAttribute('roles')))) { ?>  
                    <li><a href="/gestioncategories">Gestion des cat&eacute;gories</a></li>  
                    <li><a href="/gestioncertificats">Gestion des certificats</a></li>  
                    <?php } if(!empty($user->getAttribute('roles')) && in_array('entraineur', explode(',',$user->getAttribute('roles')))) { ?>  
                    <li><a href="/valideparticipants">Valider les inscriptions</a></li>  
                    <?php } ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <?php if(!$user->isAuthenticated()) { ?>
                    <li><a href="" data-toggle="modal" data-target="#myModal">Connexion</a></li>
                <?php } else {?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nom Utilisateur <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <li><a href="/afficherprofil"><span class="glyphicon glyphicon-user"></span> Profil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/deconnection"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
                        </ul>
                    </li>
                <?php } ?>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>
        </nav>