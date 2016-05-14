<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\StringField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class BenevoleOfficielFormBuilder extends FormBuilder
{
	public function build()
    {
		$this->form->add(new StringField([
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