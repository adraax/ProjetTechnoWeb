<?php
namespace Entity;

use \GJLMFramework\Entity;

class User extends Entity
{
    /* ********** Propriétés ********** */
    protected $username,
                $droits = [],
                $password,
                $roles = [];
    
    /* *********** Constantes *********** */
    const INVALID_USERNAME = 1;
                
    /* ********** Getter *********** */
    public function getUsername()
    {
        if(isset($this->username))
        {
            return $this->username;
        }
    }
    
    public function getDroits()
    {
        return $this->droits;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
    
    /* ********** Setter ********** */
    public function setUsername($username)
    {
        if(is_string($username) && !empty($username))
        {
            $this->username = $username;
        }
    }
    
    public function setPassword($password)
    {
        if(is_string($password) && !empty($password))
        {
            $this->password = $password;
        }
    }
}