<?php
namespace Entity;

use \GJLMFramework\Entity;

class User extends Entity
{
    /* ********** Propriétés ********** */
    protected $username,
                $droits = [],
                $roles = [];
    
    /* *********** Constantes *********** */
    const INVALID_USERNAME = 1;
                
    /* ********** Getter *********** */
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getDroits()
    {
        return $this->droits;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
}