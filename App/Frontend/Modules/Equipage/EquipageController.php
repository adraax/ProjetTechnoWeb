<?php

namespace App\Frontend\Modules\Equipage;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Competition;
use \Entity\User;
use \Entity\Competiteur;
use \Entity\Equipage;
use \FormBuilder\EquipageFormBuilder;

class EquipageController extends BaseController
{
	public function voirequipageAction(HTTPRequest $request)
	{
		//A faire : voir l'équipage
		if($request->getMethod() == 'POST' && $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($this->app->getUser()->getAttribute('id_equipage'));
			$this->app->getUser()->removeAttribute('id_equipage');
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function ajoutequipageAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST' && $request->postExists('id_competition'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
			//pour le test
			$id_user = 4;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
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
			
			if($request->postExists('nb_places'))
			{
				$equipage = new Equipage([
					'id_competition' => (int)$request->getPostData('id_competition'),
					'specialite' => $request->getPostData('specialite'),
					'categorie' => $request->getPostData('categorie'),
					'nb_places' => $request->getPostData('nb_places')
				]);
			}
			else
			{
				$equipage = new Equipage([
					'id_competition' => (int)$request->getPostData('id_competition'),
					'specialite' => $competiteur->getSpecialite(),
					'categorie' => $competiteur->getCategorie()
				]);
			}
			
			$formBuilder = new EquipageFormBuilder($equipage);
			$formBuilder->build();
			
			$form = $formBuilder->getForm();
			
			if($request->getMethod() == 'POST' && $form->isValid())
			{
				//On vérifie si le compétiteur n'est pas déjà inscrit à la compétition
				if(!$competitionmanager->isInscrit($competiteur->getId(), $request->getPostData('id_competition')))
				{
					//On vérifie que le compétiteur s'inscrit dans sa catégorie (ou supérieure)
					if($competiteur->categorieValide($request->getPostData('categorie')))
					{
						$equipagemanager = $this->managers->getManagerOf('Equipage');
						$id_equipage = $equipagemanager->save($equipage);
						$equipagemanager->addParticipant((int)$competiteur->getId(), $id_equipage);
						
						$this->app->getUser()->setFlash('L\'équipage a bien été ajouté.', 'alert-success');
						
						if($equipage->getNb_places()>1)
						{
							$this->app->getUser()->setAttribute('id_equipage', $id_equipage);
							$this->app->getHttpResponse()->redirect('/gestioninvites');
						}
						else
						{
							$this->app->getUser()->setAttribute('id_equipage', $id_equipage);
							$this->app->getHttpResponse()->redirect('/voirequipage');
						}
					}
					else
						$this->app->getUser()->setFlash('Vous ne pouvez vous inscrire dans une catégorie inférieure à la votre.', 'alert-danger');
				}
				else
					$this->app->getUser()->setFlash('Vous ne pouvez vous inscrire qu\'une seule fois à une compétition.', 'alert-danger');
			}
			//Ajout d'un champ caché pour récupérer l'id_competition à chaque fois
			$form = $form->createView();
			$form .= '<input type="hidden" name="id_competition" value="'.$request->getPostData('id_competition').'" />';
			$this->page->addVar('form', $form);
			$this->page->addVar('infocompet', $infocompet);
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function gestioninvitesAction(HTTPRequest $request)
	{
		//A faire : liste des invités, possibilité de supprimer et ajouter
		//Vérifier que l'équipage n'est pas monoplace
		if($request->getMethod() == 'POST' && $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($this->app->getUser()->getAttribute('id_equipage'));
			$this->app->getUser()->removeAttribute('id_equipage');
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function gestionparticipantsAction(HTTPRequest $request)
	{
		//A faire : liste des participants, possibilité de supprimer et ajouter
		//Vérifier que l'équipage n'est pas monoplace
		if($request->getMethod() == 'POST' && $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($this->app->getUser()->getAttribute('id_equipage'));
			$this->app->getUser()->removeAttribute('id_equipage');
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
}