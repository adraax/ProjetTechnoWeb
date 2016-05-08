<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\DatePickerField;
use \GJLMFramework\TextField;
use \GJLMFramework\RadioField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class CompetitionFormBuilder extends FormBuilder
{
    public function build()
    {
		$radio = new RadioField([
			'label' => "Niveau :",
			'name' => "niveau",
			'validators' => [
                new NotNullValidator('Il faut cocher une case.')
            ]
		]);
		$radio->addBouton('departemental');
		$radio->addBouton('regional');
		$radio->addBouton('national');
		$radio->addBouton('international');
		
        $this->form->add($radio)
		->add(new TextField([
            'label' => "Adresse :",
            'name' => "adresse",
            'validators' => [
                new NotNullValidator('Il faut entrer une adresse.')
            ]
        ]))
		->add(new StringField([
            'label' => "Code postal :",
            'name' => "code_postal",
            'maxLength' => 5,
            'validators' => [
                new MaxLengthValidator('Le code postal est trop long.', 5),
                new NotNullValidator('Il faut entrer un code postal.')
            ]
        ]))
        ->add(new StringField([
            'label' => "Ville :",
            'name' => "ville",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('La ville est trop longue.', 30),
                new NotNullValidator('Il faut entrer une ville.')
            ]
        ]))
		->add(new DatePickerField([
            'label' => 'Date de la compétition : ',
            'name' => 'date_competition',
            'maxLength' => 10,
            'validators' => [
                new MaxLengthValidator('La date de la compétition ne doit pas dépasser 10 caractères.', 10),
                new NotNullValidator('La date de le compétition ne doit pas être vide.')
            ]
        ]))
		->add(new StringField([
            'label' => "Météo :",
            'name' => "meteo",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('La météo est trop longue.', 30)
            ]
        ]))
		->add(new StringField([
            'label' => "Type d'hébergement :",
            'name' => "type_hebergement",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('Le type d\'hébergement est trop long.', 30)
            ]
        ]))
		->add(new StringField([
            'label' => "Mode de transport :",
            'name' => "mode_transport",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('Le mode de transport est trop long.', 30),
				new NotNullValidator('Le mode de transport ne doit pas être vide.')
            ]
        ]))
		->add(new StringField([
            'label' => "Club organisateur :",
            'name' => "club_organisateur",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('Le club organisateur est trop long.', 30),
				new NotNullValidator('Le club organisateur ne doit pas être vide.')
            ]
        ]));
    }
}