<?php

namespace App\Frontend\Modules\Competition;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Competition;
use \Entity\User;
use \Entity\Competiteur;
use \FormBuilder\CompetitionFormBuilder;

class CompetitionController extends BaseController
{
	public function ajoutcompetitionAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST')
        {
            $competition = new Competition([
                'niveau' => $request->getPostData('niveau'),
                'adresse' => $request->getPostData('adresse'),
				'code_postal' => $request->getPostData('code_postal'),
				'ville' => $request->getPostData('ville'),
				'date_competition' => $request->getPostData('date_competition'),
				'meteo' => $request->getPostData('meteo'),
				'type_hebergement' => $request->getPostData('type_hebergement'),
				'mode_transport' => $request->getPostData('mode_transport'),
				'club_organisateur' => $request->getPostData('club_organisateur')
            ]);
        }
        else
        {
            $competition = new Competition;
        }

		$formBuilder = new CompetitionFormBuilder($competition);
        $formBuilder->build();
        
        $form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
            $competitionmanager = $this->managers->getManagerOf('Competition');
			$competitionmanager->save($form->getEntity());
			$this->app->getUser()->setFlash('La compétition a bien été ajoutée.', 'alert-success');
        }
        
        $this->page->addVar('form', $form->createView());
	}
	
	public function listecompetitionsAction(HTTPRequest $request)
	{
		//A faire : Afficher un bouton 'Ajouter compétition' si user = admin, secretaire et/ou entraineur
		//A faire : Afficher un bouton 'Supprimer compétition' + 'Supprimer vieilles compétitions' si user = admin et/ou secretaire
		
		//Sélection des compétitions par niveau
		$form = '<input type="checkbox" name="niveau[]" id="departemental" value="departemental"';
		if($request->postExists('niveau') && in_array('departemental', $request->getPostData('niveau')))
			$form .= ' checked ';
		$form .= '/> <label for="departemental">Niveau départemental</label><br />
			<input type="checkbox" name="niveau[]" id="regional" value="regional"';
		if($request->postExists('niveau') && in_array('regional', $request->getPostData('niveau')))
			$form .= ' checked ';
		$form .= '/> <label for="regional">Niveau régional</label><br />
			<input type="checkbox" name="niveau[]" id="national" value="national"';
		if($request->postExists('niveau') && in_array('national', $request->getPostData('niveau')))
			$form .= ' checked ';
		$form .= '/> <label for="national">Niveau national</label><br />
			<input type="checkbox" name="niveau[]" id="international" value="international"';
		if($request->postExists('niveau') && in_array('international', $request->getPostData('niveau')))
			$form .= ' checked ';
		$form .= '/> <label for="international">Niveau international</label><br />';
		
		$this->page->addVar('form', $form);
		
		$competitionmanager = $this->managers->getManagerOf('Competition');
		$competitions = [];
		
		if($request->postExists('niveau'))
		{
			foreach($request->getPostData('niveau') as $niveau)
			{
				$competitions = array_merge($competitions, $competitionmanager->getListByNiveau($niveau));
			}			
		}
		else
		{
			$competitions = $competitionmanager->getList();
		}
		
		$listecompetitions = '';
		//Affichage des compétitions sélectionnées
		foreach($competitions as $competition)
		{
			if($competition->getDate_competition()>date('Y-m-d'))
			{
				$listecompetitions .= '<div class="panel panel-primary">';
			}
			else
			{
				if($competition->getDate_competition()==date('Y-m-d'))
					$listecompetitions .= '<div class="panel panel-warning">';
				else
					$listecompetitions .= '<div class="panel panel-danger">';
			}
			$listecompetitions .= '<div class="panel-heading"><h3 class="panel-title">Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
			$listecompetitions .= 'Niveau : '.$competition->getNiveau().'<br />';
			$listecompetitions .= 'Ville : '.$competition->getVille().' '.$competition->getCode_postal().'<br /><br />';
			
			$listecompetitions .= '<form method="post" action="/affichecompetition">';
			$listecompetitions .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
			$listecompetitions .= '<button type="submit" class="btn btn-default">Voir cette compétition</button>';
			$listecompetitions .= '</form>';
			$listecompetitions .= '</div></div>';
		}
		
		$this->page->addVar('listecompetitions', $listecompetitions);
	}
	
	public function affichecompetitionAction(HTTPRequest $request)
	{
		if($request->getMethod() == 'POST' && $request->postExists('id_competition'))
		{
			//Affichage des infos de la compétition 
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$competition = $competitionmanager->getUnique($request->getPostData('id_competition'));
			$affichecompetition = '<div class="jumbotron"><h3>Compétition du '.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3>';
			$affichecompetition .= $competition->afficheCompetition().'<br /><br />';
			
			//Lien pour inscrire un équipage
			//Récupération du compétiteur
			$id_user = $this->app->getUser()->getAttribute("id");
			//pour le test
			$id_user = 4;
			$usermanager = $this->managers->getManagerOf('User');
			$user = $usermanager->getUnique($id_user);
			
			$competiteurmanager = $this->managers->getManagerOf('Competiteur');
			$competiteur = $competiteurmanager->getByPersonneId($user->getId_personne());
			//Fin de récupération du compétiteur
			
			if(!empty($competiteur))
			{
				$affichecompetition .= '<form method="post" action="/voirequipage">';
				$affichecompetition .= '<input type="hidden" name="id_competition" value="'.$competition->getId().'" />';
				$affichecompetition .= '<button type="submit" class="btn btn-primary btn-lg">';
				if($competitionmanager->isInscrit($competiteur->getId(), $competition->getId()))
					$affichecompetition .= 'Voir l\'équipage';
				else
					$affichecompetition .= 'Inscrire un équipage';
				$affichecompetition .= '</button></form><br />';

				//Lien pour inscription au transport (seulement si le compétiteur est inscrit)
				if($competitionmanager->isTransport($competiteur->getId(), $competition->getId()))
					$affichecompetition .= '<a id="bouton_transport" class="btn btn-primary btn-lg" href="#" onclick="modiftransport('.$competiteur->getId().', '.$competition->getId().')" role="button">Annuler l\'inscription au transport</a>';
				else
				{
					//if($competitionmanager->isInscrit($competiteur->getId(), $competition->getId()))
					{
						//Vérification qu'il reste des places
						$nb_places_prises = $competitionmanager->getNb_places_prises($competition->getId());
						if($competition->getNb_places_dispo()>0)
						{
							if(($competition->getNb_places_dispo()-$nb_places_prises)>0)
								$affichecompetition .= '<a id="bouton_transport" class="btn btn-primary btn-lg" href="#" onclick="modiftransport('.$competiteur->getId().', '.$competition->getId().')" role="button">S\'inscrire au transport</a>';
							else
								$affichecompetition .= '<a id="bouton_transport" class="btn btn-primary btn-lg" href="#" role="button">Plus de place disponible !</a>';
						}
					}
				}
			}
			
			$this->page->addVar('affichecompetition', $affichecompetition);
			//$this->page->addVar('script', 'XMLHttpRequest');
			$this->page->addVar('script', 'modiftransport');
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
	
	public function voirequipageAction(HTTPRequest $request)
	{
		
	}
	
	public function ajoutequipageAction(HTTPRequest $request)
	{
		
	}
	
	public function modiftransportAction(HTTPRequest $request)
	{
		header("Content-Type: text/xml");
		if($request->getMethod() == 'POST' && $request->postExists('id_competition') && $request->postExists('id_competiteur'))
		{
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo '<Reponses>';
			$competitionmanager = $this->managers->getManagerOf('Competition');
			$competition = $competitionmanager->getUnique($request->getPostData('id_competition'));
			
			//Vérification qu'il reste des places
			$nb_places_prises = (int)$competitionmanager->getNb_places_prises($request->getPostData('id_competition'));
			if($competitionmanager->isTransport($request->getPostData('id_competiteur'), $request->getPostData('id_competition')))
				$nb_places_prises = 0;
			
			if(($competition->getNb_places_dispo()-$nb_places_prises)>0)
			{
				$competitionmanager->setTransport($request->getPostData('id_competiteur'), $request->getPostData('id_competition'));
				if($competitionmanager->isTransport($request->getPostData('id_competiteur'), $request->getPostData('id_competition')))
					echo '<Reponse name="false" />';
				else
					echo '<Reponse name="true" />';
			}
			else
				echo '<Reponse name="pasplace" />';
			echo '</Reponses>';
			exit;
		}
		else
			$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
}