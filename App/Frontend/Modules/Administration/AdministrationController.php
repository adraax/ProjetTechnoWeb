<?php

namespace App\Frontend\Modules\Administration;

use \GJLMFramework\BaseController;
use \GJLMFramework\HTTPRequest;
use \Entity\Personne;
use \Entity\Licence;
use \FormBuilder\PersonneFormBuilder;
use \FormBuilder\LicenceFormBuilder;

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
        
        $this->page->addVar('form', $form->createView());
	}
	
    public function ajoutadherentAction(HTTPRequest $request)
    {
		if($request->getMethod() == 'POST')
		{
			$licence = new Licence([
				'id_personne' => $request->getPostData('id_personne'),
				'num' => $request->getPostData('num'),
				'type' => $request->getPostData('type')
			]);
		}
		else
		{
			$licence = new Licence;
		}
		
		$personnemanager = $this->managers->getManagerOf('Personne');
		$personnes = $personnemanager->getListSansLicence();
		
		$formBuilder = new LicenceFormBuilder($licence);
		$formBuilder->buildAdmin($personnes);
		
		$form = $formBuilder->getForm();
		if($request->getMethod() == 'POST' && $form->isValid())
        {
            $licencemanager = $this->managers->getManagerOf('Licence');
			$licencemanager->add($form->getEntity());
        }
		
		$this->page->addVar('form', $form->createView());
    }
	
	public function gestionrolesAction(HTTPRequest $request)
    {
		
    }
}