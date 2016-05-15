<?php
namespace App\Frontend;

use \GJLMFramework\Application;
use \GJLMFramework\Auth;
use \GJLMFramework\Roles;

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
        $role = new Roles($this, $this->httpRequest->getRequestURI());
        
        if($auth->getAuth() && !$this->user->isAuthenticated())
        {
            $controller = new Modules\Connection\ConnectionController($this, 'Connection', 'connection');
        }  
        else
        {
            if($role->hasRoles($this->user->getAttribute('roles')))
            {
                $controller = $this->getController();
            }
            else
            {
                echo 'roles incorrects';
                var_dump($this->user->getAttribute('roles'));
                exit;
            }
        }
        
        $controller->execute();

        $this->httpResponse->setPage($controller->getPage());
        $this->httpResponse->send();
    }
}
