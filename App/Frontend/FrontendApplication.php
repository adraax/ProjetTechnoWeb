<?php
namespace App\Frontend;

use \GJLMFramework\Application;

class FrontendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
    }

    public function run()
    {
        if($this->getUser()->isAuthenticated() || $_SERVER['REQUEST_URI'] == "/inscription")
        {
            $controller = $this->getController();
        }
        else
        {
             $controller = new Modules\Connection\ConnectionController($this, 'Connection', 'connection');
        }
        
        $controller->execute();

        $this->httpResponse->setPage($controller->getPage());
        $this->httpResponse->send();
    }
}
