<?php
namespace Entity;

use \GJLMFramework\Entity;

class BenevoleOfficiel extends Entity
{
    /* ********** Propriétés ********** */
    protected $id_licence,
                $id_competition,
                $role;
                
    const ID_LICENCE_INV = 1;
    const ID_COMPETITION_INV = 2;
    const ROLE_INV = 3;
    
    /* *********** Getter ********** */
    public function getId_licence()
    {
        return $this->id_licence;
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
    public function setId_licence($id_licence)
    {
        if (!is_int($id_licence) || empty($id_licence))
        {
            $this->errors[] = self::ID_LICENCE_INV;
        }
        
        $this->id_licence = $id_licence;
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
		return !(empty($this->id_licence) || empty($this->id_competition) || empty($this->role));
	}
}