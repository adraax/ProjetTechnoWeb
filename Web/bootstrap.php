<?php
const DEFAULT_APP = 'Frontend';

//Si l'application n'est pas valide, on charge l'application par défaut, qui générera l'erreur 404
if(!isset($_GET['app']) || !file_exists(__DIR__.'/../App/'.$_GET['app']))
    $_GET['app'] = DEFAULT_APP;

//On inclut la classe permettant d'enregistrer les autoload
require __DIR__.'/../Lib/GJLMFramework/SplClassLoader.php';

//autoload du cms = namespace GJLMFramework
$GJLMFrameworkLoader = new SplClassLoader('GJLMFramework', __DIR__.'/../Lib/');
$GJLMFrameworkLoader->register();

//autoload des applications
$AppLoader = new SplClassLoader('App', __DIR__.'/..');
$AppLoader->register();

//Autoload des Model
$modelLoader = new SplClassLoader('Model', __DIR__.'/../lib/vendors');
$modelLoader->register();

//Autoload des Entity
$entityLoader = new SplClassLoader('Entity', __DIR__.'/../lib/vendors');
$entityLoader->register();

//Autoload des formulaires
$formBuilderLoader = new SplClassLoader('FormBuilder', __DIR__.'/../lib/vendors');
$formBuilderLoader->register();

$appClass = 'App\\'.$_GET['app'].'\\'.$_GET['app'].'Application';

$app = new $appClass;
$app->run();
