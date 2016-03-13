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
          <a class="navbar-brand" href="/"><span class="glyphicon glyphicon-star"> </span> Projet TechnoWeb</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">Accueil</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if(!$user->isAuthenticated()) { ?>
                <li><a href="/">Connexion</a></li>
            <?php } else {?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nom Utilisateur <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-left">
                        <li><span class="glyphicon glyphicon-user"></span> Profil</li>
                        <li role="separator" class="divider"></li>
                        <li><span class="glyphicon glyphicon-off"></span> Deconnexion</li>
                    </ul>
                </li>
            <?php } ?>
            </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <div id="content" class="container">
        <?php echo $content;?>
    </div>

    <script src="<?php echo $path;?>js/jquery.js"></script>
    <script src="<?php echo $path;?>js/bootstrap.js"></script>
</body>
</html>
