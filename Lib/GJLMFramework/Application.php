<?php
namespace GJLMFramework;

/**
* Classe de base du CMS, représente une partie fonctionnelle du SeekableIterator
* Exemple : un système de news, les profils utilisateurs ...
*
* Classe héritée par le module
*/
abstract class Application
{
    /* ********** Propriétés ********** */
    protected $httpRequest;     //Représente une requête HTTP
    protected $httpResponse;    //Représente la réponse de notre Application

    protected $name;            //Nom de l'application

    protected $user;            //Représente l'utilisateur de l'Application
    protected $config;          //Représente la configuration de l'Application


    /* *********** Getter ********** */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getConfig()
    {
        return $this->config;
    }

    /* *********** Constructeur ********** */
    public function __construct()
    {
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);

        $this->user = new User($this);
        $this->config = new Config($this);

        $this->name = '';
    }

    /* *********** Méthodes ********** */
    public function getController() //Méthode permettant de renvoyer le controller correspondant à la route demandée
    {
        $router = new Router;

        $xml = simplexml_load_file(__DIR__.'/../../App/'.$this->name.'/Config/routes.xml');

        foreach ($xml->route as $route)
        {
            $params = [];

            //Si des paramètres sont présents dans l'url
            if(isset($route['params']))
            {
                $params = explode(',', (string)$route['params']);
            }

            //on ajoute la route au router
            $router->addRoute(new Route((string)$route['url'], (string)$route['module'], (string)$route['action'], $params));
        }

        try
        {
            $matchedRoute = $router->getRoute($this->httpRequest->getRequestURI());
        }
        catch (\RuntimeException $e)
        {
            if($e->getCode() == Router::ROUTE_NOT_FOUND)
            {
                //Si aucune route n'est trouvée, on renvoie une erreur 404
                $this->httpResponse->redirect404();
            }
        }

        //on ajoute les paramètres au tableau $_GET
        $_GET = array_merge($_GET, $matchedRoute->getParams());

        //on instancie le controller correspondant à la route
        //On sépare les dossiers menant au controller par des \ car c'est un namespace
        //Le controller sera chargé grace à SplClassLoader
        $controllerClass = 'App\\'.$this->name.'\\Modules\\'.$matchedRoute->getModule().'\\'.$matchedRoute->getModule().'Controller';

        //on renvoie l'instance du controller
        return new $controllerClass($this, $matchedRoute->getModule(), $matchedRoute->getAction());
    }

    abstract function run();    //Méthode définissant le déroulement de l'Application

}
