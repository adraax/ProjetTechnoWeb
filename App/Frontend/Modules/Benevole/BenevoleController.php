<?php

namespace App\Frontend\Modules\Benevole;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Benevole;
use \Entity\BenevoleOfficiel;
use \FormBuilder\BenevoleFormBuilder;
use \FormBuilder\BenevoleOfficielFormBuilder;

class BenevoleController extends BaseController
{
	public function listebenevolesAction(HTTPRequest $request)
	{
		//Gestion de la désinscription
		if($request->getMethod() == 'POST' && $request->postExists('id_benevoleo_suppr'))
		{
			$benevolemanager = $this->managers->getManagerOf('BenevoleOfficiel');
			$benevolemanager->delete($request->getPostData('id_benevoleo_suppr'));
			$this->app->getUser()->setFlash('Vous avez bien été supprimé(e) des bénévoles pour cette compétition.', 'alert-success');
		}
		
		//Gestion de la suppression d'un bénévole
		if($request->getMethod() == 'POST' && $request->postExists('id_benevole_suppr'))
		{
			$benevolemanager = $this->managers->getManagerOf('Benevole');
			$benevolemanager->delete($request->getPostData('id_benevole_suppr'));
			$this->app->getUser()->setFlash('Le bénévole a bien été supprimé pour cette compétition.', 'alert-success');
		}
		
		if(($request->getMethod() == 'POST' && $request->postExists('id_competition')) || $this->app->getUser()->getAttribute('id_competition') != null)
		{
			if($this->app->getUser()->getAttribute('id_competition') != null)
			{
				$id_competition = $this->app->getUser()->getAttribute('id_competition');
				$this->app->getUser()->removeAttribute('id_competition');
			}
			else
				$id_competition = $request->getPostData('id_competition');
			
			//Récupération de la licence
			$id_user = $this->app->getUser()->getAttribute("id");
	//pour le test
	$id_user = 4;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$personnemanager = $this->managers->getManagerOf('Personne');
			$personne = $personnemanager->getUnique($user->getId_personne());
			
			$licencemanager = $this->managers->getManagerOf('Licence');
			
			$licence = $licencemanager->getByPersonneId($personne->getId());
			if(empty($licence))
				$num_licence_benevole = null;
			else
				$num_licence_benevole = $licence[0]->getNum();
			
			//Affichage du récap de la compétition
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$competition = $competitionmanager->getUnique($id_competition);
			
			$infocompet = '<div class="panel panel-primary">';
			$infocompet .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
			$infocompet .= 'Niveau : '.$competition->getNiveau().'<br />';
			$infocompet .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
			$infocompet .= '<form method="post" action="/affichecompetition">';
			$infocompet .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$infocompet .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
			$infocompet .= '</form>';
			$infocompet .= '</div></div>';
			
			//Affichage du tableau des bénévoles officiels
			$tabbenevoles = '<div class="panel panel-default"><div class="panel-heading">Bénévoles officiels</div><table class="table">';
			$tabbenevoles .= '<tr><th>Nom</th><th>Prénom</th><th>Rôle</th><th></th></tr>';
			
			$benevolemanager = $this->managers->getManagerOf('BenevoleOfficiel');
			$benevoles = $benevolemanager->getByCompetition($competition->getId());
			
			foreach($benevoles as $benevole)
			{
				$licence = $licencemanager->getUnique($benevole->getId_licence());
				$personne = $personnemanager->getUnique($licence->getId_personne());
				
				$tabbenevoles .= '<tr><td>'.$personne->getNom().'</td><td>'.$personne->getPrenom().'</td><td>'.$benevole->getRole().'</td><td>';
				
				//Si l'utilisateur est le bénévole, il peut se désinscrire
				if($num_licence_benevole == $licence->getNum())
				{
					$tabbenevoles .= '<form method="post" action="/listebenevoles">';
					$tabbenevoles .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
					$tabbenevoles .= '<input type="hidden" name="id_benevoleo_suppr" value="'.$benevole->getId().'" />';
					$tabbenevoles .= '<button type="submit" class="btn btn-danger">Se désinscrire des bénévoles</button></form>';
				}
				
				$tabbenevoles .= '</td></tr>';
			}
			
			$tabbenevoles .= '</table></div><br />';
			
			
			//Affichage du tableau des bénévoles non licenciés
			$tabbenevoles .= '<div class="panel panel-default"><div class="panel-heading">Bénévoles non licenciés</div><table class="table">';
			$tabbenevoles .= '<tr><th>Nom</th><th>Prénom</th><th>Rôle</th><th></th></tr>';
			
			$benevolemanager = $this->managers->getManagerOf('Benevole');
			$benevoles = $benevolemanager->getByCompetition($competition->getId());
			
			foreach($benevoles as $benevole)
			{
				$personne = $personnemanager->getUnique($benevole->getId_personne());
				
				$tabbenevoles .= '<tr><td>'.$personne->getNom().'</td><td>'.$personne->getPrenom().'</td><td>'.$benevole->getRole().'</td><td>';
				
				//Si l'utilisateur secrétaire, il peut supprimer un bénévole
				if($user->hasRole('secretaire'))
				{
					$tabbenevoles .= '<form method="post" action="/listebenevoles">';
					$tabbenevoles .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
					$tabbenevoles .= '<input type="hidden" name="id_benevole_suppr" value="'.$benevole->getId().'" />';
					$tabbenevoles .= '<button type="submit" class="btn btn-danger">Supprimer ce bénévole</button></form>';
				}
				
				$tabbenevoles .= '</td></tr>';
			}
			
			$tabbenevoles .= '</table></div>';
						
			$this->page->addVar('infocompet', $infocompet);
			$this->page->addVar('tabbenevoles', $tabbenevoles);
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}

	public function ajoutbenevoleAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST' && $request->postExists('id_competition'))
		{
			//Affichage du récap de la compétition
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$competition = $competitionmanager->getUnique($request->getPostData('id_competition'));
			
			$infocompet = '<div class="panel panel-primary">';
			$infocompet .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
			$infocompet .= 'Niveau : '.$competition->getNiveau().'<br />';
			$infocompet .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
			$infocompet .= '<form method="post" action="/affichecompetition">';
			$infocompet .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$infocompet .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
			$infocompet .= '</form>';
			$infocompet .= '</div></div>';
			
			if($request->postExists('id_personne'))
			{
				$benevole = new Benevole([
					'id_personne' => $request->getPostData('id_personne'),
					'id_competition' => $request->getPostData('id_competition'),
					'role' => $request->getPostData('role')
				]);
			}
			else
			{
				$benevole = new Benevole;
			}
			
			//Récupération des personnes qui ne sont pas déjà bénévoles pour cette compétition
			$personnemanager = $this->managers->getManagerOf('Personne');
			$personnes = $personnemanager->getListBenevolesDispos($competition->getId());

			//Construction du formulaire
			$formBuilder = new BenevoleFormBuilder($benevole);
			$formBuilder->setPersonnes($personnes);
			$formBuilder->build();
			
			$form = $formBuilder->getForm();
			$benevole = $form->getEntity();
			
			if($request->getMethod() == 'POST' && $request->postExists('id_personne') && $form->isValid())
			{
				$benevole->setId_competition($competition->getId());
				$benevolemanager = $this->managers->getManagerOf('Benevole');
				$benevolemanager->save($benevole);
				$this->app->getUser()->setFlash('Le bénévole a bien été ajouté.', 'alert-success');
				
				//On reforme le formulaire si on a ajouté un bénévole (la liste des personnes change)
				$personnes = $personnemanager->getListBenevolesDispos($competition->getId());
				$formBuilder = new BenevoleFormBuilder($benevole);
				$formBuilder->setPersonnes($personnes);
				$formBuilder->build();
				
				$form = $formBuilder->getForm();
			}
			
			//On ajoute un champ caché pour récupérer le numéro de la compétition
			$form = $form->createView();

			if(count($personnes)<=0)
				$form = '<p>Il n\'y a plus de personne disponible.</p>';

			$form .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			
			$this->page->addVar('form', $form);
			$this->page->addVar('infocompet', $infocompet);
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function ajoutbenevoleofficielAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST' && $request->postExists('id_competition'))
		{
			//Affichage du récap de la compétition
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$competition = $competitionmanager->getUnique($request->getPostData('id_competition'));
			
			$infocompet = '<div class="panel panel-primary">';
			$infocompet .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
			$infocompet .= 'Niveau : '.$competition->getNiveau().'<br />';
			$infocompet .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
			$infocompet .= '<form method="post" action="/affichecompetition">';
			$infocompet .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$infocompet .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
			$infocompet .= '</form>';
			$infocompet .= '</div></div>';
			
			if($request->postExists('envoi'))
			{
				$benevole = new BenevoleOfficiel([
					'id_licence' => $request->getPostData('id_licence'),
					'id_competition' => $request->getPostData('id_competition'),
					'role' => $request->getPostData('role')
				]);
			}
			else
			{
				$benevole = new BenevoleOfficiel;
			}
			
			$benevolemanager = $this->managers->getManagerOf('BenevoleOfficiel');
			
			//Récupération de la licence
			$id_user = $this->app->getUser()->getAttribute("id");
	//pour le test
	$id_user = 4;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$personnemanager = $this->managers->getManagerOf('Personne');
			$personne = $personnemanager->getUnique($user->getId_personne());
			
			$licencemanager = $this->managers->getManagerOf('Licence');
			$licence = $licencemanager->getByPersonneId($personne->getId());
			
			//Si l'adhérent est déjà bénévole, on le redirige sur la liste des bénévoles
			if($benevolemanager->isBenevole($licence[0]->getNum(), $competition->getId()))
			{
				$this->app->getUser()->setFlash('Vous êtes déjà bénévole pour cette compétition.', 'alert-danger');
				$this->app->getUser()->setAttribute('id_competition', $competition->getId());
				$this->app->getHttpResponse()->redirect('/listebenevoles');
			}
			
			//Construction du formulaire
			$formBuilder = new BenevoleOfficielFormBuilder($benevole);
			$formBuilder->build();
			
			$form = $formBuilder->getForm();
			$benevole = $form->getEntity();
			
			if($request->getMethod() == 'POST' && $request->postExists('envoi') && $form->isValid())
			{
				$benevole->setId_competition($competition->getId());
				$benevole->setId_licence($licence[0]->getNum());
				
				$benevolemanager->save($benevole);
				$this->app->getUser()->setFlash('Vous avez bien été ajouté(e) en tant que bénévole.', 'alert-success');
				
				$this->app->getUser()->setAttribute('id_competition', $competition->getId());
				$this->app->getHttpResponse()->redirect('/listebenevoles');
			}
			
			//On ajoute un champ caché pour récupérer le numéro de la compétition et savoir si c'est le premier envoi
			$form = $form->createView();

			$form .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$form .= '<input type="hidden" name="envoi" value="true" />';
			
			$this->page->addVar('form', $form);
			$this->page->addVar('infocompet', $infocompet);
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
}