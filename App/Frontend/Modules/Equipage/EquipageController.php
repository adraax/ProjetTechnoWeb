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
		//Gestion invitation refusée
		if($request->getMethod() == 'POST' && $request->postExists('id_invite_refuse'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
			//pour le test
			$id_user = 5;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteInvite($request->getPostData('id_invite_refuse'), $request->getPostData('id_equipage'));
			
			$this->app->getUser()->setFlash('Vous avez refusé cette invitation.', 'alert-danger');
			$this->app->getHttpResponse()->redirect('/listecompetitions');
		}
		
		//Gestion invitation acceptée
		if($request->getMethod() == 'POST' && $request->postExists('id_invite_accepte'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
			//pour le test
			$id_user = 5;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteInvite($request->getPostData('id_invite_accepte'), $request->getPostData('id_equipage'));
			
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
			
			$equipagemanager->addParticipant($competiteur->getId(), $request->getPostData('id_equipage'), $valide);
			
			$this->app->getUser()->setFlash('Vous avez accepté cette invitation.', 'alert-success');
		}
		
		//Gestion de la désinscription de l'équipage
		if($request->getMethod() == 'POST' && $request->postExists('id_equipage_suppr'))
		{
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->delete($request->getPostData('id_equipage_suppr'));
			$this->app->getUser()->setFlash('L\'équipage a bien été supprimé.', 'alert-success');
			$this->app->getHttpResponse()->redirect('/listecompetitions');
		}
		
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
			
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
			//pour le test
			$id_user = 5;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
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
			
			//Affichage validité équipage
			$infoequipage = '<div class="jumbotron"><h3>Equipage pour la compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3>';

			if($equipage->getNb_places()<=$equipagemanager->nbParticipantsValides($equipage->getId()))
				$infoequipage .= '<div class="panel panel-success"><div class="panel-heading">L\'équipage est valide</div></div>';
			else
				$infoequipage .= '<div class="panel panel-danger"><div class="panel-heading">L\'équipage n\'est pas valide : il manque des participants</div></div>';
			
			//Affichage informations de l'équipage
			$infoequipage .= $equipage->afficheEquipage();
			$infoequipage .= '<strong>Nombre de participants validés :</strong> '.$equipagemanager->nbParticipantsValides($equipage->getId()).'<br />';
			
			//Si l'équipage est mono-place, on affiche le compétiteur et un bouton pour désinscrire l'équipage, sinon un bouton pour voir les participants et les invités
			if($equipage->getNb_places()>1)
			{
				if($equipagemanager->isParticipant($competiteur->getId(), $equipage->getId()))
				{
					//Bouton de gestion des invités
					$infoequipage .= '<br /><form method="post" action="/gestioninvites">';
					$infoequipage .= '<input type="hidden" name="id_equipage" value="'.$id_equipage.'" />';
					$infoequipage .= '<button type="submit" class="btn btn-default">Voir les invites</button>';
					$infoequipage .= '</form><br />';
				}
				else
					if($equipagemanager->isInvite($competiteur->getId(), $equipage->getId()))
					{
						//Bouton pour accepter l'invitation
						$infoequipage .= '<br /><form method="post" action="/voirequipage">';
						$infoequipage .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
						$infoequipage .= '<input type="hidden" name="id_invite_accepte" value="'.$competiteur->getId().'" />';
						$infoequipage .= '<button type="submit" class="btn btn-default">Accepter l\'invitation</button>';
						$infoequipage .= '</form>';
						//Bouton pour refuser l'invitation
						$infoequipage .= '<form method="post" action="/voirequipage">';
						$infoequipage .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
						$infoequipage .= '<input type="hidden" name="id_invite_refuse" value="'.$competiteur->getId().'" />';
						$infoequipage .= '<button type="submit" class="btn btn-default">Refuser l\'invitation</button>';
						$infoequipage .= '</form><br />';
					}
				
				//Bouton de gestion des participants
				$infoequipage .= '<form method="post" action="/gestionparticipants">';
				$infoequipage .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
				$infoequipage .= '<button type="submit" class="btn btn-default">Voir les participants</button>';
				$infoequipage .= '</form></div>';
			}
			else
			{
				//Information sur le compétiteur
				$competiteurParticipant = $competiteurmanager->getUnique($equipage->getParticipants()[0]);
				$personnemanager = $this->managers->getManagerOf('Personne');
				$personne = $personnemanager->getUnique($competiteurParticipant->getNum_personne());
				
				$infoequipage .= '<strong>Compétiteur :</strong> '.$personne->getNom().' '.$personne->getPrenom().' '.$competiteurParticipant->getCategorie().'<br /><br />';
				
				//Bouton de désinscription (uniquement si le compétiteur est participant de l'équipage)
				if($equipagemanager->isParticipant($competiteur->getId(), $equipage->getId()))
				{
					$infoequipage .= '<form method="post" action="/voirequipage">';
					$infoequipage .= '<input type="hidden" name="id_equipage_suppr" value="'.$equipage->getId().'" />';
					$infoequipage .= '<button type="submit" class="btn btn-danger">Désinscrire l\'équipage</button>';
					$infoequipage .= '</form></div>';
				}
			}
			
			$this->page->addVar('infocompet', $infocompet);
			$this->page->addVar('infoequipage', $infoequipage);
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
				$infocompet .= '</form><br />';
				//Bouton retour à l'équipage
				$infocompet .= '<form method="post" action="/voirequipage">';
				$infocompet .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
				$infocompet .= '<button type="submit" class="btn btn-default">Retour à l\'équipage</button>';
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
				$this->app->getUser()->setFlash('L\'équipage a été supprimé car il n\'y a plus de participant.', 'alert-danger');
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
				$infocompet .= '</form><br />';
				//Bouton retour à l'équipage
				$infocompet .= '<form method="post" action="/voirequipage">';
				$infocompet .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
				$infocompet .= '<button type="submit" class="btn btn-default">Retour à l\'équipage</button>';
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