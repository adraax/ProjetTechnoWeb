<?php
namespace App\Frontend;

use \GJLMFramework\Application;
use \GJLMFramework\Auth;

class FrontendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
    }

    public function run()
    {
        $auth = new Auth($this, $this->httpRequest->getRequestURI());
        
        if($auth->getAuth())
        {
            $controller = new Modules\Connection\ConnectionController($this, 'Connection', 'connection');
        }  
        else
        {
            $controller = $this->getController();
        }
        
        /*if($this->getUser()->isAuthenticated() || $_SERVER['REQUEST_URI'] == "/inscription")
        {
            $controller = $this->getController();
        }
        else
        {
             $controller = new Modules\Connection\ConnectionController($this, 'Connection', 'connection');
        }*/
        
        $controller->execute();

        $this->httpResponse->setPage($controller->getPage());
        $this->httpResponse->send();
    }
}
