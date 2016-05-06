<?php

namespace App\Frontend\Modules\Administration;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Personne;
use \FormBuilder\PersonneFormBuilder;

class AdministrationController extends BaseController
{
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
			$personnemanager->add($form->getEntity());
        }
		else
		{
			
		}
        
        $this->page->addVar('form', $form->createView());
	}
	
    public function ajoutadherentAction(HTTPRequest $request)
    {
		
    }
	
	public function gestionrolesAction(HTTPRequest $request)
    {
		
    }
}