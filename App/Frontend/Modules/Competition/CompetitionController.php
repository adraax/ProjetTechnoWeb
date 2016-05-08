<?php

namespace App\Frontend\Modules\Competition;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Competition;
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
				$listecompetitions .= '<div class="panel panel-info">';
			}
			else
			{
				if($competition->getDate_competition()==date('Y-m-d'))
					$listecompetitions .= '<div class="panel panel-warning">';
				else
					$listecompetitions .= '<div class="panel panel-danger">';
			}
			$listecompetitions .= '<div class="panel-heading"><h3 class="panel-title">'.strftime("%d/%m/%Y",strtotime($competition->getDate_competition())).'</h3></div><div class="panel-body">';
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
		if($request->getMethod() == 'POST')
		{
			echo $request->getPostData('id_competition');
		}
		else  echo 'coucou';
			//$this->app->getHttpResponse()->redirect('/listecompetitions');
	}
}