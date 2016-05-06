<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\DatePickerField;
use \GJLMFramework\StringField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class LicenceFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Numéro de licence : ',
            'name' => 'num',
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le numéro de licence ne doit pas dépasser 30 caractères.', 30),
                new NotNullValidator('Le numéro de licence ne peut pas être vide.')
            ]
        ]))
        ->add(new DatePickerField([
            'label' => 'Date de naissance : ',
            'name' => 'date',
            'maxLength' => 10,
            'validators' => [
                new MaxLengthValidator('La date de naissance ne doit pas dépasser 10 caractères.', 10),
                new NotNullValidator('La date de naissance ne doit pas être vide.')
            ]
        ]));
    }
	
	public function buildAdmin($personne)
	{
		$option = new ListField([
			'label' => "Liste des personnes n'ayant pas de licence :",
			'name' => "id_personne",
			'validators' => [
                new NotNullValidator('Il faut choisir une personne.')
            ]
		]);
		foreach($personne as $perso)
		{
			$nom = $perso->getNom().' '.$perso->getPrenom();
			$option->addOption((int)$perso->getId(), $nom);
		}
		
		$this->form->add($option)
		->add(new StringField([
            'label' => 'Numéro de licence : ',
            'name' => 'num',
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le numéro de licence ne doit pas dépasser 30 caractères.', 30),
                new NotNullValidator('Le numéro de licence ne peut pas être vide.')
            ]
        ]))
		->add(new StringField([
            'label' => 'Type de licence : ',
            'name' => 'type',
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le type de licence ne doit pas dépasser 30 caractères.', 30),
                new NotNullValidator('Le type de licence ne peut pas être vide.')
            ]
        ]));
	}
}