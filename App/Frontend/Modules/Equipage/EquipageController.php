<?php

namespace App\Frontend\Modules\Equipage;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Competition;
use \Entity\User;
use \Entity\Competiteur;
use \Entity\Personne;
use \Entity\Equipage;
use \FormBuilder\EquipageFormBuilder;

class EquipageController extends BaseController
{
	public function voirequipageAction(HTTPRequest $request)
	{
		//A faire : voir l'équipage + si équipage valide (nb_participants_valides > nb_places)
		//A faire : pour bouton voir invités : l'utilisateur doit être participant, sinon si invité : bouton accepter/refuser invitation
		if(($request->getMethod() == 'POST' && $request->postExists('id_equipage')) || $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			if($this->app->getUser()->getAttribute('id_equipage') != null)
			{
				$id_equipage = $this->app->getUser()->getAttribute('id_equipage');
				$this->app->getUser()->removeAttribute('id_equipage');
			}
			else
				$id_equipage = $request->getPostData('id_equipage');
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($id_equipage);
			/*
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
			*/
			//Pour le test
			$form = '<form method="post" action="/gestioninvites">';
			$form .= '<input type="hidden" name="id_equipage" value="'.$id_equipage.'" />';
			$form .= '<button type="submit" class="btn btn-default">Voir les invites</button>';
			$form .= '</form>';
			echo $form;
			$form = '<form method="post" action="/gestionparticipants">';
			$form .= '<input type="hidden" name="id_equipage" value="'.$id_equipage.'" />';
			$form .= '<button type="submit" class="btn btn-default">Voir les participants</button>';
			$form .= '</form>';
			echo $form;
			//Fin pour le test
			
			$this->page->addVar('infocompet', $infocompet);
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
						
						//Si le compétiteur est majeur, sa participation est directement validée, autrement un entraîneur devra le faire
						$personnemanager = $this->managers->getManagerOf('Personne');
						$personne = $personnemanager->getUnique($user->getId_personne());
						$date_nais = $personne->getDate_naissance();
						$date_nais = \DateTime::createFromFormat('Y-m-d',$date_nais);
						$aujourdhui = new \DateTime();
						$interv = new \DateInterval('P18Y');
						if($date_nais->add($interv) > $aujourdhui)
							$valide = false;
						else
							$valide = true;
						
						$equipagemanager->addParticipant((int)$competiteur->getId(), $id_equipage, $valide);
						
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
		//Si cela a été demandé, on supprime l'invité sélectionné
		if($request->getMethod() == 'POST' && $request->postExists('id_invite_suppr'))
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteInvite($request->getPostData('id_invite_suppr'), $request->getPostData('id_equipage'));
		}
		
		//Si cela a été demandé, on ajoute l'invité sélectionné
		if($request->getMethod() == 'POST' && $request->postExists('id_add_invite'))
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->addInvite($request->getPostData('id_add_invite'), $request->getPostData('id_equipage'));
		}
		
		if(($request->getMethod() == 'POST' && $request->postExists('id_equipage')) || $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			if($this->app->getUser()->getAttribute('id_equipage') != null)
				$id_equipage = $this->app->getUser()->getAttribute('id_equipage');
			else
				$id_equipage = $request->getPostData('id_equipage');
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($id_equipage);
			
			//Si l'équipage est mono-place, il n'y a pas d'invités à gérer
			if($equipage->getNb_places()<=1)
				$this->app->getHttpResponse()->redirect('/voirequipage');
			else
			{
				if($this->app->getUser()->getAttribute('id_equipage') != null)
					$this->app->getUser()->removeAttribute('id_equipage');
				
				//Affichage du récap de la compétition
				$competitionmanager = $this->managers->getManagerOf('Competition');
				$competition = $competitionmanager->getUnique($equipage->getId_competition());
				
				$infocompet = '<div class="panel panel-primary">';
				$infocompet .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
				$infocompet .= 'Niveau : '.$competition->getNiveau().'<br />';
				$infocompet .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
				$infocompet .= '<form method="post" action="/affichecompetition">';
				$infocompet .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
				$infocompet .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
				$infocompet .= '</form>';
				$infocompet .= '</div></div>';
				
				//Remplissage du tableau des invités
				$competiteurmanager = $this->managers->getManagerOf('Competiteur');
				$personnemanager = $this->managers->getManagerOf('Personne');
				
				$tabinvites = '<div class="panel panel-default"><div class="panel-heading">Invités</div><table class="table">';
				$tabinvites .= '<tr><th>Nom</th><th>Prénom</th><th>Catégorie</th><th></th></tr>';
				if(!empty($equipage->getInvites()))
					foreach($equipage->getInvites() as $invite)
					{
						$competiteur = $competiteurmanager->getUnique($invite);
						$personne = $personnemanager->getUnique($competiteur->getNum_personne());
						
						$tabinvites .= '<tr><td>'.$personne->getNom().'</td><td>'.$personne->getPrenom().'</td><td>'.$competiteur->getCategorie().'</td>';
						$tabinvites .= '<td><form method="post" action="/gestioninvites">';
						$tabinvites .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
						$tabinvites .= '<input type="hidden" name="id_invite_suppr" value="'.$competiteur->getId().'" />';
						$tabinvites .= '<button type="submit" class="btn btn-danger">Annuler cette invitation</button>';
						$tabinvites .= '</form></td></tr>';
					}
				$tabinvites .= '</table></div>';
				
				//Ajout du bouton pour inviter un compétiteur
				$competiteurs = $competiteurmanager->getListDispo($competition->getId()); //Compétiteurs qui ne sont pas inscrits à la compétition
				
				$form = '<form method="post" action="/gestioninvites">';
				$form .= '<label for="id_add_invite">Ajouter un invité</label><br />';
				$form .= '<select class="form-control" name="id_add_invite" id="id_add_invite">';
				//Ajout d'un champ caché pour récupérer l'id_equipage à chaque fois
				foreach($competiteurs as $competiteur)
				{
					//On vérifie que le compétiteur n'est pas déjà invité
					if(!$equipagemanager->isInvite($competiteur->getId(), $equipage->getId()))
					{
						//On vérifie que la catégorie est valide et que le certificat médical est fourni
						if($competiteur->categorieValide($equipage->getCategorie()) && $competiteur->getCertif_med())
						{
							$personne = $personnemanager->getUnique($competiteur->getNum_personne());
							
							$form .= '<option value="'.$competiteur->getId().'">'.$personne->getNom().' '.$personne->getPrenom().
							' '.$competiteur->getCategorie().' '.$competiteur->getSpecialite().'</option>';
						}
					}
				}
				$form .= '</select>';
				$form .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
				$form .= '<button type="submit" class="btn btn-default">Inviter</button></form>';
				
				$this->page->addVar('form', $form);	
				$this->page->addVar('infocompet', $infocompet);
				$this->page->addVar('tabinvites', $tabinvites);
			}	
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function gestionparticipantsAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST' && $request->postExists('id_participant_suppr'))
		{
			//Si cela a été demandé, on supprime le participant. Si l'équipage est vide, on le supprime aussi
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteParticipant($request->getPostData('id_participant_suppr'), $request->getPostData('id_equipage'));
			$equipage = $equipagemanager->getUnique($request->getPostData('id_equipage'));
			if($equipage->getNb_participants()<1)
			{
				$equipagemanager->delete($equipage->getId());
				$this->app->getHttpResponse()->redirect('/listecompetitions');
			}
		}
		
		if(($request->getMethod() == 'POST' && $request->postExists('id_equipage')) || $this->app->getUser()->getAttribute('id_equipage') != null)
		{
			if($this->app->getUser()->getAttribute('id_equipage') != null)
				$id_equipage = $this->app->getUser()->getAttribute('id_equipage');
			else
				$id_equipage = $request->getPostData('id_equipage');
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipage = $equipagemanager->getUnique($id_equipage);
			
			//Si l'équipage est mono-place, il n'y a pas de participants à gérer
			if($equipage->getNb_places()<=1)
				$this->app->getHttpResponse()->redirect('/voirequipage');
			else
			{
				if($this->app->getUser()->getAttribute('id_equipage') != null)
					$this->app->getUser()->removeAttribute('id_equipage');
				
				//Affichage du récap de la compétition
				$competitionmanager = $this->managers->getManagerOf('Competition');
				$competition = $competitionmanager->getUnique($equipage->getId_competition());
				
				$infocompet = '<div class="panel panel-primary">';
				$infocompet .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
				$infocompet .= 'Niveau : '.$competition->getNiveau().'<br />';
				$infocompet .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
				$infocompet .= '<form method="post" action="/affichecompetition">';
				$infocompet .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
				$infocompet .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
				$infocompet .= '</form>';
				$infocompet .= '</div></div>';
				
				//Remplissage du tableau des participants
				$competiteurmanager = $this->managers->getManagerOf('Competiteur');
				$personnemanager = $this->managers->getManagerOf('Personne');
				
				//Récupération du compétiteur
				$id_user = $this->app->getUser()->getAttribute("id");
				//pour le test
				$id_user = 4;
				$usermanager = $this->managers->getManagerOf('User');
				$user = $usermanager->getUnique($id_user);		
				$id_user_competiteur = $competiteurmanager->getByPersonneId($user->getId_personne())->getId();
				//Fin de récupération du compétiteur
				
				$tabparticipants = '<div class="panel panel-default"><div class="panel-heading">Participants</div><table class="table">';
				$tabparticipants .= '<tr><th>Nom</th><th>Prénom</th><th>Catégorie</th><th>Inscription valide</th><th></th></tr>';
				foreach($equipage->getParticipants() as $participant)
				{
					$competiteur = $competiteurmanager->getUnique($participant);
					$personne = $personnemanager->getUnique($competiteur->getNum_personne());
					
					$tabparticipants .= '<tr><td>'.$personne->getNom().'</td><td>'.$personne->getPrenom().'</td><td>'.$competiteur->getCategorie().'</td><td>';
					if($equipagemanager->participantValide($participant, $equipage->getId()))
						$tabparticipants .= 'Oui';
					else
						$tabparticipants .= 'En cours de validation';
					$tabparticipants .= '</td><td>';
					
					//On ajoute un bouton pour que l'utilisateur puisse se désinscrire
					
					if($participant == $id_user_competiteur)
					{
						$tabparticipants .= '<form method="post" action="/gestionparticipants">';
						$tabparticipants .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
						$tabparticipants .= '<input type="hidden" name="id_participant_suppr" value="'.$participant.'" />';
						$tabparticipants .= '<button type="submit" class="btn btn-danger">Se désinscrire de l\'équipage</button></form>';
					}
				}
				$tabparticipants .= '</td></tr></table></div>';
						
				$this->page->addVar('infocompet', $infocompet);
				$this->page->addVar('tabparticipants', $tabparticipants);
			}	
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
}