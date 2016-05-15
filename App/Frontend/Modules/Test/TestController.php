<?php
namespace App\Frontend\Modules\Test;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \GJLMFramework\PDOFactory;

class TestController extends BaseController
{
    public function indexAction(HTTPRequest $request)
    {
        $db = PDOFactory::getMysqlConnection('localhost', 'projet_techno_web', 'cadoc', 'perceval');

        var_dump($db);
    }
    
}
