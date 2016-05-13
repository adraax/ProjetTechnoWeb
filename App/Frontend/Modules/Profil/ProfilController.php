<?php
namespace App\Frontend\Modules\Profil;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \FormBuilder\PersonneFormBuilder;

class ProfilController extends BaseController
{
	public function afficherAction(HTTPRequest $request)
	{
		//Nombre d'invitations dans un équipage
		$nb_invites = '';
		//Si l'utilisateur est un compétiteur
		$is_competiteur = false;
		
		$id_user = $this->app->getUser()->getAttribute('id');
	//Pour le test
	$id_user = 4;
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
		
		$id_user = $this->app->getUser()->getAttribute("id");
	//Pour le test
	$id_user = 4;
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id_user);
	
		//Récupération des infos de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getUnique($user->getId_personne());
		
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
		
		//Si compétiteur, ajout définir un objectif pour la saison
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
		
		$id_user = $this->app->getUser()->getAttribute("id");
	//Pour le test
	$id_user = 4;
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
			$competition = $competitionmanager->getUnique($competition);
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
			$id_user = $this->app->getUser()->getAttribute("id");
	//Pour le test
	$id_user = 4;
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
			
			$equipagemanager->addParticipant($competiteur->getId(), $request->getPostData('id_equipage_accepte'), $valide);
			
			$this->app->getUser()->setFlash('Vous avez accepté cette invitation.', 'alert-success');
		}
		
		//Gestion invitation refusée
		if($request->getMethod() == 'POST' && $request->postExists('id_equipage_refuse'))
		{
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
	//Pour le test
	$id_user = 4;
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
		
		$id_user = $this->app->getUser()->getAttribute("id");
	//Pour le test
	$id_user = 4;
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
		$tabinvitations .= '<tr><th>Date de la compétition</th><th>Lien vers la compétition</th><th></th><th></th></tr>';
		
		$competitionmanager = $this->managers->getManagerOf('Competition');
		$equipages = $equipagemanager->getInvitesByCompetiteurId($competiteur->getId());

		foreach($equipages as $equipage)
		{
			$competition = $competitionmanager->getUnique($equipage->getId_competition());
			$tabinvitations .= '<tr><td>'.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</td>';
			$tabinvitations .= '<td><form method="post" action="/affichecompetition">';
			$tabinvitations .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$tabinvitations .= '<button type="submit" class="btn btn-default">Voir la compétition</button>';
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