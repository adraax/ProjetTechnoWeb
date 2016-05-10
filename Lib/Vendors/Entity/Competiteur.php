<?php
namespace Entity;

use \GJLMFramework\Entity;

class Competiteur extends Entity
{
	protected $categorie,
				$specialite,
				$objectif_saison,
				$num_personne,
				$certif_med; //0 ou 1 dans la BD, mais gestion en boolean dans l'appli
                
    const CATEGORIE_INV = 1;
    const SPECIALITE_INV = 2;
    const OBJECTIF_SAISON_INV = 3;
	const NUM_PERSONNE_INV = 4;
	const CERTIF_MED_INV = 5;
    
                
    /* ********** Setter ********** */
    public function setCategorie($categorie)
    {
        if($categorie != 'veteran' && $categorie != 'senior' && $categorie != 'junior' && $categorie != 'cadet' && $categorie != 'minime')
        {
            $this->errors[] = self::CATEGORIE_INV;
        }
        
        $this->categorie = $categorie;
    }
    
    public function setSpecialite($specialite)
    {
        if($specialite != 'kayak' && $specialite != 'canoe')
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
	
	public function setNum_personne($num_personne)
    {
        if(!is_int($num_personne) || empty($num_personne))
        {
            $this->errors[] = self::NUM_PERSONNE_INV;
        }
        
        $this->num_personne = $num_personne;
    }
	
	public function setCertif_med($certif_med)
    {
        if(!is_bool($certif_med) || empty($certif_med))
        {
            $this->errors[] = self::CERTIF_MED_INV;
        }
        else
			if($certif_med)
				$this->certif_med = 1;
			else
				$this->certif_med = 0;
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
	
	public function getNum_personne()
    {
        return $this->num_personne;
    }
	
	public function getCertif_med()
    {
		if($this->certif_med == 1)
			return true;
		else
			return false;
    }
	
	public function categorieValide($categorie)
	{
		if($this->categorie == 'minime')
			return true;
		
		if($this->categorie == 'cadet')
		{
			if($categorie != 'minime')
				return true;
			else
				return false;
		}
		
		if($this->categorie == 'junior')
		{
			if($categorie != 'minime' && $categorie != 'cadet')
				return true;
			else
				return false;
		}
			
		if($this->categorie == 'senior')
		{
			if($categorie == 'senior' || $categorie == 'veteran')
				return true;
			else
				return false;
		}
			
		if($this->categorie == 'veteran')
		{
			if($categorie == 'veteran')
				return true;
			else
				return false;
		}
	}
	
	public function isValid()
	{
		return !(empty($this->specialite) || empty($this->categorie) || empty($this->num_personne) || empty($this->certif_med));
	}
}