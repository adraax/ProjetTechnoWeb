<?php

namespace App\Frontend\Modules\Administration;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Personne;
use \Entity\Licence;
use \Entity\User;
use \FormBuilder\PersonneFormBuilder;
use \FormBuilder\LicenceFormBuilder;
use \FormBuilder\RoleFormBuilder;

class AdministrationController extends BaseController
{
	public function ajoutpersonneAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST')
        {
            $personne = new Personne([
                'nom' => $request->getPostData('nom'),
                'prenom' => $request->getPostData('prenom'),
				'adresse' => $request->getPostData('adresse'),
				'date_naissance' => $request->getPostData('date_naissance'),
				'email' => $request->getPostData('email'),
				'num_tel' => $request->getPostData('num_tel'),
				'sexe' => $request->getPostData('sexe')
            ]);
        }
        else
        {
            $personne = new Personne;
        }

		$formBuilder = new PersonneFormBuilder($personne);
        $formBuilder->build();
        
        $form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
            $personnemanager = $this->managers->getManagerOf('Personne');
			$personnemanager->save($form->getEntity());
			$this->app->getUser()->setFlash('La personne '.$request->getPostData('nom').' '.$request->getPostData('prenom').' a bien été ajoutée.', 'alert-success');
        }
        
        $this->page->addVar('form', $form->createView());
	}
	
    public function ajoutadherentAction(HTTPRequest $request)
    {
		if($request->getMethod() == 'POST')
		{
			$licence = new Licence([
				'id_personne' => $request->getPostData('id_personne'),
				'num' => $request->getPostData('num'),
				'type' => $request->getPostData('type')
			]);
		}
		else
		{
			$licence = new Licence;
		}
		
		$personnemanager = $this->managers->getManagerOf('Personne');
		$personnes = $personnemanager->getListSansLicence();
		
		$formBuilder = new LicenceFormBuilder($licence);
		$formBuilder->buildAdmin($personnes);
		
		$form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
            $licencemanager = $this->managers->getManagerOf('Licence');
			$licencemanager->save($form->getEntity());
			$this->app->getUser()->setFlash('La licence '.$request->getPostData('num').' a bien été ajoutée.', 'alert-success');
        }
		
		$this->page->addVar('form', $form->createView());
    }
	
	public function gestionrolesAction(HTTPRequest $request)
    {
		$usermanager = $this->managers->getManagerOf('User');
		$users = $usermanager->getList();
		
		if($request->getMethod() == 'POST')
		{
			if($request->postExists('id'))
			{
				$user = $usermanager->getUnique($request->getPostData('id'));
				$user->setRoles('');
				$user->setConfirm_password($user->getPassword());
				if($request->postExists('roles'))
					foreach($request->getPostData('roles') as $role)
						$user->addRole($role);
			}
			else
				$user = new User;
		}
		else
		{
			$user = $users[0];
		}
		
		$formBuilder = new RoleFormBuilder($user);
		$formBuilder->setUsers($users);
		$formBuilder->build();
		
		$form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
			$usermanager->save($user);
			$this->app->getUser()->setFlash($user->getUsername().' a le(s) rôle(s) '.$user->getRoles().'.', 'alert-success');
        }
		
		$this->page->addVar('form', $form->createView());
		//$this->page->addVar('script', 'XMLHttpRequest');
		$this->page->addVar('script', 'returnroles');
    }
	
	//Pour l'ajax
	public function returnrolesAction(HTTPRequest $request)
	{
		header("Content-Type: text/xml");
		if($request->getMethod() == 'POST' && $request->postExists('num'))
		{
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo '<roles>';
			$num = ($request->postExists('num')) ? $request->getPostData('num') : NULL;

			if ($num) 
			{
				$usermanager = $this->managers->getManagerOf('User');
				$user = $usermanager->getUnique($num);
				$roles = explode(',',$user->getRoles());
				foreach($roles as $role)
				{
					echo '<role name="' . $role . '" />';
				}
			}

			echo '</roles>';
			exit;
		}
		else
			$this->app->getHttpResponse()->redirect('/gestionroles');
	}
}