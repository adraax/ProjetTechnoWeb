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
		echo '<h3>Profil :</h3>';
		$id = $this->app->getUser()->getAttribute('id');
		//Récupération des rôles, pour savoir s'il faut aussi afficher les informations pour compétiteur
		$userManager = $this->managers->getManagerOf('User');
		$user = $userManager->getUnique($id);
		//Récupération des infos de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getByPersonneId($id);
		echo $personne->affichePersonne();
		//Afficher les infos de la personne
		if($user->hasRole('competiteur'))
		{
			//Afficher les infos du compétiteur
		}
	}
	
    public function modifierAction(HTTPRequest $request)
    {
		$id = $this->app->getUser()->getAttribute("id");
		//Récupération des infos de la personne
		$personneManager = $this->managers->getManagerOf('Personne');
		$personne = $personneManager->getByPersonneId($id);
		//Construction du formulaire
		$formBuilder = new PersonneFormBuilder($personne);
        $formBuilder->build();
        $form = $formBuilder->getForm();
		$this->page->addVar('form', $form->createView());
    }
}