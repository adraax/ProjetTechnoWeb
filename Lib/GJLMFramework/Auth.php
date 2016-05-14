<?php
namespace GJLMFramework;

class Auth extends ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $auth;
    protected $uri;
    
    /* ********** Constructeur ********** */
    public function __construct(Application $app, $uri)
    {
        parent::__construct($app);
        
        $this->setUri($uri);
    }
    
    /* ********** Getter ********** */
    public function getAuth()
    {
        return $this->auth;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    /* ********** Setter ********** */
    public function setUri($uri)
    {
        if(!is_string($uri) || empty($uri))
        {
            throw new InvalidArgumentException("L'uri doit être une chaine de caractères");
        }
        else
        {
            $xml = simplexml_load_file(__DIR__.'/../../App/'.$this->app->getName().'/Config/routes.xml');
            
            foreach ($xml->route as $route)
            {
                if($uri === (string)$route['url'])
                {
                    if((string)$route['auth'] === "no")
                    {
                        $this->auth = false;
                    }
                    else
                    {
                        $this->auth = true;
                    }
                }
            }
        }
    }
}