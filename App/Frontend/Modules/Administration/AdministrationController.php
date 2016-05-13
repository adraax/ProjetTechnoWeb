<?php

namespace App\Frontend\Modules\Administration;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Personne;
use \Entity\Licence;
use \Entity\User;
use \Entiy\Competiteur;
use \FormBuilder\PersonneFormBuilder;
use \FormBuilder\LicenceFormBuilder;
use \FormBuilder\RoleFormBuilder;
use \FormBuilder\CategorieFormBuilder;

class AdministrationController extends BaseController
{
	//Pour l'entraîneur
	public function valideparticipantsAction(HTTPRequest $request)
	{
		//Validation du participant si cela a été demandé
		if($request->getMethod() == 'POST' && $request->postExists('id_competiteur') && $request->postExists('id_equipage'))
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->setParticipantValide($request->getPostData('id_competiteur'), $request->getPostData('id_equipage'));
			$this->app->getUser()->setFlash('Cette inscription a bien été validée.', 'alert-success');
		}
		
		//Affichage du tableau des bénévoles non licenciés
		$tabparticipants = '<div class="panel panel-default"><div class="panel-heading">Inscriptions à valider</div><table class="table">';
		$tabparticipants .= '<tr><th>Nom</th><th>Prénom</th><th>Equipage</th><th></th></tr>';
		
		$competiteurmanager = $this->managers->getManagerOf('Competiteur');
		$personnemanager = $this->managers->getManagerOf('Personne');
		
		//Récupération des participants non valides
		$equipagemanager = $this->managers->getManagerOf('Equipage');
		$nonvalides = $equipagemanager->getNonValides();
		
		foreach($nonvalides as $nonvalide)
		{
			$competiteur = $competiteurmanager->getUnique($nonvalide['num_competiteur']);
			$personne = $personnemanager->getUnique($competiteur->getNum_personne());
			
			$tabparticipants .= '<tr><td>'.$personne->getNom().'</td><td>'.$personne->getPrenom().'</td>';
			
			//Ajout du bouton voir l'équipage
			$tabparticipants .= '<td><form method="post" action="/gestionparticipants">';
			$tabparticipants .= '<input type="hidden" name="id_equipage" value="'.$nonvalide['num_equipage'].'" />';
			$tabparticipants .= '<button type="submit" class="btn btn-default">Voir l\'équipage</button></form></td>';
			
			//Ajout bouton valider l'inscription
			$tabparticipants .= '<td><form method="post" action="/valideparticipants">';
			$tabparticipants .= '<input type="hidden" name="id_equipage" value="'.$nonvalide['num_equipage'].'" />';
			$tabparticipants .= '<input type="hidden" name="id_competiteur" value="'.$nonvalide['num_competiteur'].'" />';
			$tabparticipants .= '<button type="submit" class="btn btn-success">Valider l\'inscription</button></form></td>';		
		}
		
		$tabparticipants .= '</table></div>';
		
		$this->page->addVar('tabparticipants', $tabparticipants);
	}
	
	//Pour le secrétaire
	public function gestioncategoriesAction(HTTPRequest $request)
    {
		$competiteurmanager = $this->managers->getManagerOf('Competiteur');
		$personnemanager = $this->managers->getManagerOf('Personne');
		$competiteurs = $competiteurmanager->getList();
		
		if($request->getMethod() == 'POST')
		{
			if($request->postExists('id'))
			{
				$competiteur = $competiteurmanager->getByPersonneId($request->getPostData('id'));
				if($request->postExists('categorie'))
					$competiteur->setCategorie($request->getPostData('categorie'));
			}
			else
				$competiteur = new Competiteur;
		}
		else
		{
			$competiteur = $competiteurs[0];
		}
		
		$formBuilder = new CategorieFormBuilder($competiteur);
		foreach($competiteurs as $competiteur)
		{
			$personne = $personnemanager->getUnique($competiteur->getNum_personne());
			$formBuilder->addPersonne($personne);
		}
		$formBuilder->build();
		
		$form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
			$competiteur = $competiteurmanager->getByPersonneId($request->getPostData('id'));
			if($request->postExists('categorie'))
				$competiteur->setCategorie($request->getPostData('categorie'));

			$competiteurmanager->save($competiteur);
			$this->app->getUser()->setFlash('Ce compétiteur est maintenant dans la catégorie '.$competiteur->getCategorie().'.', 'alert-success');
        }
		
		$this->page->addVar('form', $form->createView());
		//$this->page->addVar('script', 'XMLHttpRequest');
		$this->page->addVar('script', 'returncategorie');
    }
	
	//Pour l'administrateur
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
	
	public function returncategorieAction(HTTPRequest $request)
	{
		header("Content-Type: text/xml");
		if($request->getMethod() == 'POST' && $request->postExists('num'))
		{
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo '<categories>';
			$num = ($request->postExists('num')) ? $request->getPostData('num') : NULL;

			if ($num) 
			{
				$competiteurmanager = $this->managers->getManagerOf('Competiteur');
				$competiteur = $competiteurmanager->getByPersonneId($num);
				
				echo '<categorie name="' . $competiteur->getCategorie() . '" />';
			}

			echo '</categories>';
			exit;
		}
		else
			$this->app->getHttpResponse()->redirect('/gestioncategories');
	}
}