<?php
namespace Entity;

use \GJLMFramework\Entity;

class Competiteur extends Personne
{
	protected $categorie,
				$specialite,
				$objectif_saison;
                
    const CATEGORIE_INV = 1;
    const SPECIALITE_INV = 2;
    const OBJECTIF_SAISON_INV = 3;
    
                
    /* ********** Setter ********** */
    public function setCategorie($categorie)
    {
        if($categorie != 'Veteran' && $categorie != 'Senior' && $categorie != 'Junior' && $categorie != 'Cadet' && $categorie != 'Minime')
        {
            $this->errors[] = self::CATEGORIE_INV;
        }
        
        $this->categorie = $categorie;
    }
    
    public function setSpecialite($specialite)
    {
        if($specialite != 'Kayak' && $specialite != 'Canoe')
        {
            $this->errors[] = self::SPECIALITE_INV;
        }
        
        $this->specialite = $specialite;
    }
    
    public function setObjectif_saison($objectif_saison)
    {
        if(!is_string($objectif_saison) || empty($objectif_saison))
        {
            $this->errors[] = self::OBJECTIF_SAISON_INV;
        }
        
        $this->objectif_saison = $objectif_saison;
    }
    
    /* ********** Getter ********** */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    public function getSpecialite()
    {
        return $this->specialite;
    }
    
    public function getObjectif_saison()
    {
        return $this->objectif_saison;
    }
}