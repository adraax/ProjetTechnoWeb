<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\PasswordField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class UserFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => "Nom d'utilisateur :",
            'name' => "username",
            'maxLength' => 5,
            'validators' => [
                new MaxLengthValidator('tro lon batar', 2),
                new NotNullValidator('tape kelke chause salo')
            ]
        ]))
        ->add( new PasswordField([
            'label' => "Mot de passe : ",
            'name' => "password",
            'validators' => [
                new MaxLengthValidator('tro lon batar', 2),
                new NotNullValidator('tape kelke chause salo')
            ]
        ]));
        
        var_dump(new MaxLengthValidator('tro lon batar', 5));
    }
}