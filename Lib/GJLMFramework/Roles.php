<?php
namespace GJLMFramework;

class Roles extends ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $roles = [];
    protected $uri;
    
    /* ********** Constructeur ********** */
    public function __construct(Application $app, $uri)
    {
        parent::__construct($app);
        
        $this->setUri($uri);
    }
    
    /* ********** Getter ********** */
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getRoles()
    {
        return $this->roles;
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
            $this->uri = $uri;
            
            $xml = simplexml_load_file(__DIR__.'/../../App/'.$this->app->getName().'/Config/routes.xml');
            
            foreach ($xml->route as $route)
            {
                if($uri === (string)$route['url'])
                {
                    if((string)$route['roles'] === "no")
                    {
                        $this->roles = null;
                    }
                    else
                    {
                        $this->roles = explode(',', (string)$route['roles']);
                    }
                }
            }
        }
    }
    
    /* ********** Méthode ********** */
    public function hasRoles($roles)
    {
        if(!is_null($roles))
        {
            if(!is_null($this->roles))
            {
                if(is_array($roles))
                {
                    foreach ($roles as $role)
                    {
                        if(!in_array($role, $this->roles))
                        {
                            return false;
                        }
                    }
                    
                    return true;
                }
                else if(is_string($roles))
                {
                    if(!in_array($roles, $this->roles))
                        return false;
                    return true;
                }
                else
                {
                    throw new \InvalidArgumentException('Le paramètre doit être une string ou un tableau de string.');
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }
}