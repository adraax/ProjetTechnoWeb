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
        $this->page->addVar('script', 'test');
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
            
            if(!is_null($licence2))
            {
                if(!$licence2->getActivated()==true)
                {//vérification date de naissance
                    $personneManager = $this->managers->getManagerOf('Personne');
                    $personne = $personneManager->getUnique($licence2->getId_personne());
                    if($licence->getDate() == $personne->getDate_naissance())
                    {
                        $userManager = $this->managers->getManagerOf('User');
                        $user = $userManager->getByPersonneId($licence2->getId_personne());
                        
                        if(is_null($user))
                        {
                            $this->app->getUser()->setFlash("Aucun compte n'est lié à cette licence. <br/> Remplissez le formulaire suivant pour le créer.", 'alert-info');
                            $this->app->getUser()->setAttribute('num_licence', $licence2->getNum());
                            $this->app->getUser()->setAttribute('type_licence', $licence2->getType());
                            $this->app->getUser()->setAttribute('id_personne', $licence2->getId_personne());
                            $this->app->getHttpResponse()->redirect('/createuser');
                        }
                        else
                        {
                            $licenceManager = $this->managers->getManagerOf('Licence');
                            $licence2 = $licenceManager->getUnique($licence->getNum());
                            $licence2->setActivated(1);
                            $licence2->setId(5);
                            $licenceManager->save($licence2);
                        
                            $this->app->getHttpResponse()->redirect('/');
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
                    $this->app->getUser()->setFlash('Cette licence a déjà été activée', 'alert-warning');
                    $this->app->getHttpResponse()->redirect('/');
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
                    'password' => $request->getPostData('password'),
                    'confirm_password' => $request->getPostData('confirm_password')
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
                $userManager = $this->managers->getManagerOf('User');
                $user2 = $userManager->getByName($user->getUsername);
                
                if(is_null($user2))
                {
                    if($user->getConfirm_password() !== $user->getPassword())
                    {
                        $this->app->getUser()->setFlash('Les deux mots de passes doivent être identiques.', 'alert-danger');
                    }
                    else
                    {
                        $user->setId_personne($this->app->getUser()->getAttribute('id_personne'));
                        $user->addRole($this->app->getUser()->getAttribute('type_licence'));
                        $this->app->getUser()->removeAttribute('id_personne');
                        $this->app->getUser()->removeAttribute('type_licence');
                        $userManager = $this->managers->getManagerOf('User');
                        $userManager->save($user);
                        
                        $licenceManager = $this->managers->getManagerOf('Licence');
                        $licence = $licenceManager->getUnique($this->app->getUser()->getAttribute('num_licence'));
                        $licence->setActivated(1);
                        $licence->setId(5);
                        $licenceManager->save($licence);
                        
                        $this->app->getHttpResponse()->redirect('/');
                    }
                }
                else
                {
                    $this->app->getUser()->setFlash('Le nom d\'utilisateur est déjà utilisé.', 'alert-danger');
                    $this->app->getHttpResponse()->redirect('/');
                }
            }
            
            $this->page->addVar('form', $form->createView());
        }
        else
        {
            $this->app->getHttpResponse()->redirect('/');
        }
    }
    
    public function connectionAjaxAction(HTTPRequest $request)
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
        
       echo '<form method="post" action="/connection">'.$form->createView().'
                <button type="submit" class="btn btn-default">Connexion</button></form>
            <a href="/inscription">Pas de compte ? Cliquez ici pour vous inscrire</a>';
        exit;
    }
}