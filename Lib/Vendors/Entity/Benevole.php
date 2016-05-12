<?php
namespace Entity;

use \GJLMFramework\Entity;

class Benevole extends Entity
{
    /* ********** Propriétés ********** */
    protected $id_personne,
                $id_competition,
                $role;
                
    const ID_PERSONNE_INV = 1;
    const ID_COMPETITION_INV = 2;
    const ROLE_INV = 3;
    
    /* *********** Getter ********** */
    public function getId_personne()
    {
        return $this->id_personne;
    }
    
    public function getId_competition()
    {
        return $this->id_competition;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    /* ********** Setter ********** */
    public function setId_personne($id_personne)
    {
        if (!is_int($id_personne) || empty($id_personne))
        {
            $this->errors[] = self::ID_PERSONNE_INV;
        }
        
        $this->id_personne = $id_personne;
    }
    
	public function setId_competition($id_competition)
    {
        if (!is_int($id_competition) || empty($id_competition))
        {
            $this->errors[] = self::ID_COMPETITION_INV;
        }
        
        $this->id_competition = $id_competition;
    }
	
    public function setRole($role)
    {
        if(!is_string($role) || empty($role))
        {
            $this->errors[] = self::ROLE_INV;
        }
        
        $this->role = $role;
    }
    
	public function isValid()
	{
		return !(empty($this->id_personne) || empty($this->id_competition) || empty($this->role));
	}
}