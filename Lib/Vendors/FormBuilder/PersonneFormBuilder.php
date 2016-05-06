<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\TextField;
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
                new MaxLengthValidator('Le nom d\'utilisateur est trop long.', 30),
                new NotNullValidator('Il faut entrer un nom.')
            ]
        ]))
        ->add(new StringField([
            'label' => "Prenom :",
            'name' => "prenom",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator('Le prénom d\'utilisateur est trop long.', 30),
                new NotNullValidator('Il faut entrer un prénom.')
            ]
        ]))
		->add(new TextField([
            'label' => "Adresse :",
            'name' => "adresse",
            'validators' => [
                new NotNullValidator('Il faut entrer une adresse.')
            ]
        ]))
		->add(new StringField([
            'label' => "Email :",
            'name' => "email",
			'maxLength' => 30,
            'validators' => [
				new MaxLengthValidator('L\'email est trop long.', 30),
                new NotNullValidator('Il faut entrer un email.')
            ]
        ]))
		->add(new StringField([
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