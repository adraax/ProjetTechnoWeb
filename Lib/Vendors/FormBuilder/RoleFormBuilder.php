<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\CheckboxField;
use \GJLMFramework\ListField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class RoleFormBuilder extends FormBuilder
{
	protected $users = [];
	
	public function setUsers($users)
	{
		$this->users = $users;
	}
	
	public function build()
    {
        $option = new ListField([
			'label' => "Liste des utilisateurs :",
			'name' => "id",
			'validators' => [
                new NotNullValidator('Il faut choisir un utilisateur.')
            ]
		]);
		foreach($this->users as $user)
		{
			$nom = $user->getUsername();
			$option->addOption((int)$user->getId(), $nom);
		}
		$option->setOnchange('request(this);');
		
		$roles = new CheckboxField([
			'label' => "Roles :",
			'name' => "roles",
			'validators' => [
                new NotNullValidator('Il faut choisir au moins un rôle.')
            ]
		]);
		$roles->addBouton('admin');
		$roles->addBouton('entraineur');
		$roles->addBouton('secretaire');
		$roles->addBouton('competiteur');
		
		$this->form->add($option)
		->add($roles);
    }
}