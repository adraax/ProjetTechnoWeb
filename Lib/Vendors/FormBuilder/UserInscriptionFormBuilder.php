<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\PasswordField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class UserInscriptionFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => "Nom d'utilisateur :",
            'name' => "username",
            'maxLength' => 30,
            'validators' => [
                new MaxLengthValidator("Le nom d'utilisateur ne doit pas dépasser 30 caractères.", 30),
                new NotNullValidator("Le nom d'utilisateur ne peut pas être vide.")
            ]
        ]))
        ->add( new PasswordField([
            'label' => "Mot de passe : ",
            'name' => "password",
            'validators' => [
                new NotNullValidator('Le mot de passe ne peut pas être vide.')
            ]
        ]))
        ->add( new PasswordField([
            'label' => "Confirmez le mot de passe : ",
            'name' => "confirm_password",
            'validators' => [
                new NotNullValidator('Le mot de passe ne peut pas être vide.')
            ]
        ]));
    }
}