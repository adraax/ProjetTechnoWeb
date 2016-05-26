<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\DatePickerField;
use \GJLMFramework\TextField;
use \GJLMFramework\EmailField;
use \GJLMFramework\NumberField;
use \GJLMFramework\RadioField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class PersonneFormBuilder extends FormBuilder
{
    public function build()
    {
		$radio = new RadioField([
			'label' => "Sexe :",
			'name' => "sexe",
			'validators' => [
                new NotNullValidator('Il faut cocher une case.')
            ]
		]);
		$radio->addBouton('H');
		$radio->addBouton('F');
		
        $this->form->add(new StringField([
            'label' => "Nom :",
            'name' => "nom",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le nom de l\'utilisateur est trop long.', 30),
                new NotNullValidator('Il faut entrer un nom.')
            ]
        ]))
        ->add(new StringField([
            'label' => "Prenom :",
            'name' => "prenom",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le prénom de l\'utilisateur est trop long.', 30),
                new NotNullValidator('Il faut entrer un prénom.')
            ]
        ]))
		->add(new DatePickerField([
            'label' => 'Date de naissance : ',
            'name' => 'date_naissance',
            'maxLength' => 10,
            'validators' => [
                new MaxLengthValidator('La date de naissance ne doit pas dépasser 10 caractères.', 10),
                new NotNullValidator('La date de naissance ne doit pas être vide.')
            ]
        ]))
		->add(new TextField([
            'label' => "Adresse :",
            'name' => "adresse",
            'validators' => [
                new NotNullValidator('Il faut entrer une adresse.')
            ]
        ]))
		->add(new EmailField([
            'label' => "Email :",
            'name' => "email",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('L\'email est trop long.', 30),
                new NotNullValidator('Il faut entrer un email.')
            ]
        ]))
		->add(new NumberField([
            'label' => "Numéro de téléphone :",
            'name' => "num_tel",
			'maxLength' => 10,
            'validators' => [
				new MaxLengthValidator('Le numéro de téléphone est trop long.', 10),
                new NotNullValidator('Il faut entrer un numéro de téléphone.')
            ]
        ]))
		->add($radio);
    }
}