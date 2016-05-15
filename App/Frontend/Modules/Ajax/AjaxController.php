<?php
namespace App\Frontend\Modules\Ajax;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;

class AjaxController extends BaseController
{
    public function getNavbarAction(HTTPRequest $request)
    {
        $user = $this->app->getUser();
        require __DIR__.'/Views/getnavbar.php';
        exit;
    }
}