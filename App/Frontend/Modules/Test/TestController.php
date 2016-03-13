<?php
namespace App\Frontend\Modules\Test;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \GJLMFramework\PDOFactory;

class TestController extends BaseController
{
    public function indexAction(HTTPRequest $request)
    {
        if($this->app->getUser()->isAuthenticated()==false)
        {
            $this->page->setContentFile(__DIR__.'\Views\connection.php');
            return ;
        }
        $db = PDOFactory::getMysqlConnection('localhost', 'projet_techno_web', 'cadoc', 'perceval');

        var_dump($db);
        $this->app->getUser()->setAuthenticated(false);
    }
    
    public function connectionAction(HTTPRequest $request)
    {
        
    }
}
