<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>css/bootstrap-theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>css/style.css"/>
    <title>
        <?php echo isset($title) ? $title." - Projet TechnoWeb" : "Projet TechnoWeb"; ?>
    </title>
</head>
<body>
    <div id="navbar">
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
                    <li><a href="/afficherprofil">Profil</a></li>
                    <li><a href="/listecompetitions">Comp&eacute;titions</a></li>
					<?php if(!empty($user->getAttribute('roles')) && in_array('admin', explode(',',$user->getAttribute('roles')))) { ?>
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
                            <li><span class="glyphicon glyphicon-user"></span> Profil</li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/deconnection"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>
                        </ul>
                    </li>
                <?php } ?>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>
        </nav>
    </div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Connexion</h4>
      </div>
      <div class="modal-body" id="mod">
        ...
      </div>
    </div>
  </div>
</div>

    <div id="content" class="container">
        <?php
        if($user->hasFlash())
        {
            $type = $user->getAttribute('flash_type');
            $flash = $user->getFlash();
            
            echo '<div class="alert alert-dismissible '.$type.'" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            '.$flash.'</div>';
        }
        echo $content;?>
    </div>

    <script src="<?php echo $path;?>js/jquery.js"></script>
    <script src="<?php echo $path;?>js/bootstrap.js"></script>
    <script src="js/connexion.js"></script>
    <?php if(isset($script))
    {
            echo '<script src="'.$path.'js/'.$script.'.js"></script>';
    }
    ?>
</body>
</html>