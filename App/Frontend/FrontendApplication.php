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
        
        if($auth->getAuth() && !$this->user->isAuthenticated())
        {
            $controller = new Modules\Connection\ConnectionController($this, 'Connection', 'connection');
        }  
        else
        {
            $controller = $this->getController();
        }
        
        $controller->execute();

        $this->httpResponse->setPage($controller->getPage());
        $this->httpResponse->send();
    }
}
