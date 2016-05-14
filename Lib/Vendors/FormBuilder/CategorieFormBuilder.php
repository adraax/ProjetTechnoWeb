<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\RadioField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;
use \Entity\Personne;

class CategorieFormBuilder extends FormBuilder
{
	protected $personnes = [];
	
	public function addPersonne(Personne $personne)
	{
		$this->personnes[] = $personne;
	}
	
	public function build()
    {
        $option = new ListField([
			'label' => "Liste des compétiteurs :",
			'name' => "id",
			'validators' => [
                new NotNullValidator('Il faut choisir un compétiteur.')
            ]
		]);
		foreach($this->personnes as $personne)
		{
			$nom = $personne->getNom().' '.$personne->getPrenom();
			$option->addOption((int)$personne->getId(), $nom);
		}
		$option->setOnchange('request(this);');
		
		$categories = new RadioField([
			'label' => "Catégories :",
			'name' => "categorie",
			'validators' => [
                new NotNullValidator('Il faut choisir une catégorie.')
            ]
		]);
		$categories->addBouton('minime');
		$categories->addBouton('cadet');
		$categories->addBouton('junior');
		$categories->addBouton('senior');
		$categories->addBouton('veteran');
		
		$this->form->add($option)
		->add($categories);
    }
}