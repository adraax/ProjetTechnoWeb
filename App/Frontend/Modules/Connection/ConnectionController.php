<?php
namespace App\Frontend\Modules\Connection;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\User;
use \FormBuilder\UserFormBuilder;

class ConnectionController extends BaseController
{
    public function connectionAction(HTTPRequest $request)
    {
        if($request->getMethod() == 'POST')
        {
            $user = new User([
                'username' => $request->getPostData('username'),
                'password' => $request->getPostData('password')
            ]);
        }
        else
        {
            $user = new User;
        }
        
        $formBuilder = new UserFormBuilder($user);
        $formBuilder->build();
        
        $form = $formBuilder->getForm();
        
        if($request->getMethod() == 'POST' && $form->isValid())
        {
            $this->app->getHttpResponse()->redirect('/');
        }
        
        $this->page->addVar('form', $form->createView());
    }
    
    public function inscriptionAction(HTTPRequest $request)
    {
        
    }
}