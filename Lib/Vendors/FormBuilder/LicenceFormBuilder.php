<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\DatePickerField;
use \GJLMFramework\StringField;
use \GJLMFramework\NumberField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class LicenceFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new NumberField([
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
		
		$type = new ListField([
			'label' => "Type de la licence :",
			'name' => "type",
			'validators' => [
                new NotNullValidator('Il faut choisir un type.')
            ]
		]);
		$type->addOption('competiteur', 'Competiteur');
		$type->addOption('entraineur', 'Entraineur');
		$type->addOption('secretaire', 'Secretaire');
		$type->addOption('loisir', 'Loisir');
		
		$this->form->add($option)
		->add($type)
		->add(new NumberField([
            'label' => 'Numéro de licence : ',
            'name' => 'num',
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le numéro de licence ne doit pas dépasser 30 caractères.', 30),
                new NotNullValidator('Le numéro de licence ne peut pas être vide.')
            ]
        ]));
	}
}