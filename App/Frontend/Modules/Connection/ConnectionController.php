<?php
namespace App\Frontend\Modules\Connection;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\User;
use \Entity\Licence;
use \Entity\Personne;
use \FormBuilder\UserFormBuilder;
use \FormBuilder\UserInscriptionFormBuilder;
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
            $licence2 = $licenceManager->getUnique($request->getPostData('num'));
            var_dump($licence);
            
            if(!is_null($licence2))
            {
                //vérification date de naissance
                $personneManager = $this->managers->getManagerOf('Personne');
                $personne = $personneManager->getUnique($licence2->getId_personne());
                var_dump($personne);
                if($licence->getDate() == $personne->getDate_naissance())
                {
                    $userManager = $this->managers->getManagerOf('User');
                    $user = $userManager->getByPersonneId($licence->getId_personne());
                    
                    if(is_null($user))
                    {
                        $this->app->getUser()->setFlash("Aucun compte n'est lié à cette licence. <br/> Remplissez le formulaire suivant pour le créer.", 'alert-info');
                        $this->app->getUser()->setAttribute('num_licence', $licence2->getNum());
                        $this->app->getUser()->setAttribute('type_licence', $licence2->getType());
                        $this->app->getHttpResponse()->redirect('/createuser');
                    }
                }
                else
                {
                    $this->app->getUser()->setFlash('Vous ne pouvez pas vous inscrire avec cette licence', 'alert-danger');
                    $this->app->getHttpResponse()->redirect('/inscription');
                }
            }
            else
            {
                $this->app->getUser()->setFlash('Vous ne pouvez pas vous inscrire avec cette licence', 'alert-danger');
                $this->app->getHttpResponse()->redirect('/inscription');
            }
        }
        
        $this->page->addVar('form', $form->createView());
    }
    
    public function createUserAction(HTTPRequest $request)
    {  
        if(isset($_SESSION['num_licence'], $_SESSION['type_licence']))
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
            
            $formBuilder = new UserInscriptionFormBuilder($user);
            $formBuilder->build();
            
            $form = $formBuilder->getForm();
            
            if($request->getMethod() == 'POST' && $form->isValid())
            {
                $this->app->getHttpResponse()->redirect('/');
            }
            
            $this->page->addVar('form', $form->createView());
        }
        else
        {
            $this->app->getHttpResponse()->redirect('/');
        }
    }
}