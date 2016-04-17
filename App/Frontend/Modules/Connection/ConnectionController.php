<?php
namespace App\Frontend\Modules\Connection;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\User;
use \Entity\Licence;
use \FormBuilder\UserFormBuilder;
use \FormBuilder\LicenceFormBuilder;

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
        if($request->getMethod() == 'POST')
        {
            $licence = new Licence([
                'num' => $request->getPostData('num'),
                'date' => $request->getPostData('date')
                ]);
        }
        else
        {
            $licence = new Licence;
        }
        
        $formBuilder = new LicenceFormBuilder($licence);
        $formBuilder->build();
        
        $form = $formBuilder->getForm();
        
        if($request->getMethod() == 'POST' && $form->isValid())
        {
            $licenceManager = $this->managers->getManagerOf('Licence');
            $licence = $licenceManager->getUnique($request->getPostData('num'));
            var_dump($licence);
            
            if(!is_null($licence))
            {
                $personneManager = $this->managers->getManagerOf('Personne');
            }
            else
            {
                $this->app->getUser()->setFlash('Vous ne pouvez pas vous inscrire avec cette licence', 'alert-danger');
                $this->app->getHttpResponse()->redirect('/inscription');
            }
        }
        
        $this->page->addVar('form', $form->createView());
    }
}