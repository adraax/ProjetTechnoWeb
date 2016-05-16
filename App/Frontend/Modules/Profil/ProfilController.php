<?php
namespace App\Frontend\Modules\Profil;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Personne;
use \FormBuilder\PersonneFormBuilder;

class ProfilController extends BaseController
{
	public function afficherAction(HTTPRequest $request)
	{
		//Nombre d'invitations dans un équipage
		$nb_invites = '';
		//Si l'utilisateur est un compétiteur
		$is_competiteur = false;
		
		$id_user = $this->app->getUser()->getAttribute('user_id');

		//Récupération des rôles, pour savoir s'il faut aussi afficher les informations pour compétiteur
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id_user);
		
		//Récupération des infos de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getUnique($user->getId_personne());
		
		$afficher = '<div class="jumbotron"><h3>Informations personnelles</h3>';
		
		//Afficher les infos de la personne
		$afficher .= $personne->affichePersonne();
		
		if($user->hasRole('competiteur'))
		{
			$is_competiteur = true;
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			
			$competiteur = $competiteurmanager->getByPersonneId($personne->getId());
			$nb_invites = count($equipagemanager->getInvitesByCompetiteurId($competiteur->getId()));
			
			if($nb_invites == 0)
				$nb_invites = '';
			
			$afficher .= '<br />'.$competiteur->afficheCompetiteur();
		}
		
		$afficher .= '<br /><a href="/modifierprofil">Modifier</a>';
		$afficher .= '</div>';
		
		$this->page->addVar('afficher', $afficher);
		$this->page->addVar('nb_invites', $nb_invites);
		$this->page->addVar('is_competiteur', $is_competiteur);
	}
	
    public function modifierAction(HTTPRequest $request)
    {
		//Nombre d'invitations dans un équipage
		$nb_invites = '';
		//Si l'utilisateur est un compétiteur
		$is_competiteur = false;
		
		$id_user = $this->app->getUser()->getAttribute("user_id");
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id_user);
	
		//Récupération des infos de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getUnique($user->getId_personne());
		
		//Modification des éléments remplis
		if($request->getMethod() == 'POST' && $request->postExists('nom'))
			$personne->setNom($request->getPostData('nom'));
		if($request->getMethod() == 'POST' && $request->postExists('prenom'))
			$personne->setPrenom($request->getPostData('prenom'));
		if($request->getMethod() == 'POST' && $request->postExists('date_naissance'))
			$personne->setDate_naissance($request->getPostData('date_naissance'));
		if($request->getMethod() == 'POST' && $request->postExists('adresse'))
			$personne->setAdresse($request->getPostData('adresse'));
		if($request->getMethod() == 'POST' && $request->postExists('email'))
			$personne->setEmail($request->getPostData('email'));
		if($request->getMethod() == 'POST' && $request->postExists('num_tel'))
			$personne->setNum_tel($request->getPostData('num_tel'));
		if($request->getMethod() == 'POST' && $request->postExists('sexe'))
			$personne->setSexe($request->getPostData('sexe'));

		//Construction du formulaire
		$formBuilder = new PersonneFormBuilder($personne);
        $formBuilder->build();
        $form = $formBuilder->getForm();
		
		if($request->getMethod() == 'POST' && $form->isValid())
		{
			$personneManager->save($form->getEntity());
			$this->app->getUser()->setFlash('Les modifications ont bien été prises en compte.', 'alert-success');
		}
		
		$form = $form->createView();
		
		//Si compétiteur, ajout définir un objectif pour la saison + choix de la spécialité
		if($user->hasRole('competiteur'))
		{
			$is_competiteur = true;
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$competiteurManager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurManager->getByPersonneId($personne->getId());
			
			$nb_invites = count($equipagemanager->getInvitesByCompetiteurId($competiteur->getId()));
			
			if($nb_invites == 0)
				$nb_invites = '';
			
			if($request->getMethod() == 'POST' && $request->postExists('objectif_saison'))
			{
				$competiteur->setObjectif_saison($request->getPostData('objectif_saison'));
				$competiteurManager->save($competiteur);
			}
			
			$form .= '<label for="objectif_saison">Objectif pour la saison : </label>';
			$form .= '<input type="text" id="objectif_saison" name="objectif_saison" class="form-control" value="'.$competiteur->getObjectif_saison().'" /><br />';
			
			if($request->getMethod() == 'POST' && $request->postExists('specialite'))
			{
				$competiteur->setSpecialite($request->getPostData('specialite'));
				$competiteurManager->save($competiteur);
			}
			
			$form .= '<label for="specialite">Choix de la spécialité :</label><br />';
			$form .= '<label class="radio-inline"><input type="radio" id="canoe" name="specialite" value="canoe"';	
			if($competiteur->getSpecialite()=='canoe')
				$form .= ' checked';
			$form .= '/>Canoë</label>';
			
			$form .= '<label class="radio-inline"><input type="radio" id="kayak" name="specialite" value="kayak"';		
			if($competiteur->getSpecialite()=='kayak')
				$form .= ' checked';
			$form .= '/>Kayak</label><br />';
		}
		
		$this->page->addVar('form', $form);
		$this->page->addVar('nb_invites', $nb_invites);
		$this->page->addVar('is_competiteur', $is_competiteur);
    }
	
	public function voirmescompetitionsAction(HTTPRequest $request)
	{
		//Nombre d'invitations dans un équipage
		$nb_invites = '';
		//Si l'utilisateur est un compétiteur
		$is_competiteur = true;
		
		$id_user = $this->app->getUser()->getAttribute("user_id");
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id_user);
		
		//Récupération de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getUnique($user->getId_personne());
		
		//Récupération du nombre d'invitations
		$competiteurmanager = $this->managers->getManagerOf('Competiteur');
		$equipagemanager = $this->managers->getManagerOf('Equipage');
		
		$competiteur = $competiteurmanager->getByPersonneId($personne->getId());
		$nb_invites = count($equipagemanager->getInvitesByCompetiteurId($competiteur->getId()));
		
		if($nb_invites == 0)
			$nb_invites = '';

		//Affichage du tableau des équipages du compétiteur
		$tabcompetitions = '<div class="panel panel-default"><div class="panel-heading">Mes compétitions</div><table class="table">';
		$tabcompetitions .= '<tr><th>Date</th><th>Lien</th></tr>';
		
		$competitionmanager = $this->managers->getManagerOf('Competition');
		$competitions = $competitionmanager->getByCompetiteurId($competiteur->getId());

		foreach($competitions as $competition)
		{
			$competition = $competitionmanager->getUnique($competition[0]);
			$tabcompetitions .= '<tr><td>'.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</td>';
			$tabcompetitions .= '<td><form method="post" action="/affichecompetition">';
			$tabcompetitions .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$tabcompetitions .= '<button type="submit" class="btn btn-default">Voir la compétition</button>';
			$tabcompetitions .= '</form></td>';
		}
		
		$tabcompetitions .= '</table></div>';
		
		$this->page->addVar('nb_invites', $nb_invites);
		$this->page->addVar('is_competiteur', $is_competiteur);
		$this->page->addVar('tabcompetitions', $tabcompetitions);
	}
	
	public function voirmesinvitationsAction(HTTPRequest $request)
	{
		//Gestion de l'invitation acceptée
		if($request->getMethod() == 'POST' && $request->postExists('id_equipage_accepte'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("user_id");
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteInvite($competiteur->getId(), $request->getPostData('id_equipage_accepte'));
			
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
			
			//On vérifie que l'adhérent n'est pas déjà inscrit à la compétition
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$equipage = $equipagemanager->getUnique($request->getPostData('id_equipage_accepte'));
			
			if($competitionmanager->isInscrit($competiteur->getId(), $equipage->getId_competition()))
				$this->app->getUser()->setFlash('Vous êtes déjà inscrit à cette compétition.', 'alert-danger');
			else
			{
				$equipagemanager->addParticipant($competiteur->getId(), $request->getPostData('id_equipage_accepte'), $valide);
				
				$this->app->getUser()->setFlash('Vous avez accepté cette invitation.', 'alert-success');
			}
		}
		
		//Gestion invitation refusée
		if($request->getMethod() == 'POST' && $request->postExists('id_equipage_refuse'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("user_id");
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
			$equipagemanager = $this->managers->getManagerOf('Equipage');
			$equipagemanager->deleteInvite($competiteur->getId(), $request->getPostData('id_equipage_refuse'));
			
			$this->app->getUser()->setFlash('Vous avez refusé cette invitation.', 'alert-danger');
		}
		
		//Nombre d'invitations dans un équipage
		$nb_invites = '';
		//Si l'utilisateur est un compétiteur
		$is_competiteur = true;
		
		$id_user = $this->app->getUser()->getAttribute("user_id");
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id_user);
		
		//Récupération de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getUnique($user->getId_personne());
		
		//Récupération du nombre d'invitations
		$competiteurmanager = $this->managers->getManagerOf('Competiteur');
		$equipagemanager = $this->managers->getManagerOf('Equipage');
		
		$competiteur = $competiteurmanager->getByPersonneId($personne->getId());
		$nb_invites = count($equipagemanager->getInvitesByCompetiteurId($competiteur->getId()));
		
		if($nb_invites == 0)
			$nb_invites = '';
		
		//Affichage du tableau des invitations du compétiteur
		$tabinvitations = '<div class="panel panel-default"><div class="panel-heading">Mes invitations</div><table class="table">';
		$tabinvitations .= '<tr><th>Date de la compétition</th><th>Lien vers l\'équipage</th><th></th><th></th></tr>';
		
		$competitionmanager = $this->managers->getManagerOf('Competition');
		$equipages = $equipagemanager->getInvitesByCompetiteurId($competiteur->getId());

		foreach($equipages as $equipage)
		{
			$competition = $competitionmanager->getUnique($equipage->getId_competition());
			$tabinvitations .= '<tr><td>'.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</td>';
			$tabinvitations .= '<td><form method="post" action="/voirequipage">';
			$tabinvitations .= '<input type="hidden" name="id_equipage" value="'.$equipage->getId().'" />';
			$tabinvitations .= '<button type="submit" class="btn btn-default">Voir l\'équipage</button>';
			$tabinvitations .= '</form></td>';
			
			//Bouton validation / refus de l'invitations
			$tabinvitations .= '<td><form method="post" action="/voirmesinvitations">';
			$tabinvitations .= '<input type="hidden" name="id_equipage_accepte" value="'.$equipage->getId().'" />';
			$tabinvitations .= '<button type="submit" class="btn btn-success">Accepter</button>';
			$tabinvitations .= '</form></td>';
			$tabinvitations .= '<td><form method="post" action="/voirmesinvitations">';
			$tabinvitations .= '<input type="hidden" name="id_equipage_refuse" value="'.$equipage->getId().'" />';
			$tabinvitations .= '<button type="submit" class="btn btn-danger">Refuser</button>';
			$tabinvitations .= '</form></td>';
		}
		
		$tabinvitations .= '</table></div>';
		
		$this->page->addVar('nb_invites', $nb_invites);
		$this->page->addVar('is_competiteur', $is_competiteur);
		$this->page->addVar('tabinvitations', $tabinvitations);
	}
}