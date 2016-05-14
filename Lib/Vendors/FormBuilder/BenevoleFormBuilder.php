<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class BenevoleFormBuilder extends FormBuilder
{
	protected $personnes = [];
	
	public function setPersonnes($personnes)
	{
		$this->personnes = $personnes;
	}
	
	public function build()
    {
        $option = new ListField([
			'label' => "Liste des personnes :",
			'name' => "id_personne",
			'validators' => [
                new NotNullValidator('Il faut choisir une personne.')
            ]
		]);
		foreach($this->personnes as $personne)
		{
			$nom = $personne->getNom().' '.$personne->getPrenom();
			$option->addOption((int)$personne->getId(), $nom);
		}
		
		$this->form->add($option)
		->add(new StringField([
            'label' => "Role :",
            'name' => "role",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le role est trop longue.', 30),
                new NotNullValidator('Il faut entrer un role.')
            ]
        ]));
    }
}