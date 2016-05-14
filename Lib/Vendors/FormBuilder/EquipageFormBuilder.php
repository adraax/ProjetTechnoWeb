<?php
namespace FormBuilder;

use \GJLMFramework\FormBuilder;
use \GJLMFramework\RadioField;
use \GJLMFramework\MaxLengthValidator;
use \GJLMFramework\NotNullValidator;

class EquipageFormBuilder extends FormBuilder
{
    public function build()
    {
		$specialite = new RadioField([
			'label' => "Spécialite :",
			'name' => "specialite",
			'validators' => [
                new NotNullValidator('Il faut cocher une case.')
            ]
		]);
		$specialite->addBouton('canoe');
		$specialite->addBouton('kayak');
		
		$categorie = new RadioField([
			'label' => "Catégorie :",
			'name' => "categorie",
			'validators' => [
                new NotNullValidator('Il faut cocher une case.')
            ]
		]);
		$categorie->addBouton('veteran');
		$categorie->addBouton('senior');
		$categorie->addBouton('junior');
		$categorie->addBouton('cadet');
		$categorie->addBouton('minime');
		
		$nbPlaces = new RadioField([
			'label' => "Nombre de places :",
			'name' => "nb_places",
			'validators' => [
                new NotNullValidator('Il faut cocher une case.')
            ]
		]);
		$nbPlaces->addBouton('1');
		$nbPlaces->addBouton('2');
		$nbPlaces->addBouton('4');
		
        $this->form->add($specialite)
		->add($categorie)
		->add($nbPlaces);
    }
}