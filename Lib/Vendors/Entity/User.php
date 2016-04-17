<?php
namespace Entity;

use \GJLMFramework\Entity;

class User extends Entity
{
    /* ********** Propriétés ********** */
    protected $username,
                $password,
                $id_personne,
                $roles = [];
    
    /* *********** Constantes *********** */
    const INVALID_USERNAME = 1;
    const INVALID_ROLES = 2;
                
    /* ********** Getter *********** */
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getRoles()
    {
        return implode(',', $this->roles);
    }
    
    public function getId_personne()
    {
        return $this->id_personne;
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
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    
    public function setId_Personne($id)
    {
        $id = (int) $id;
        $this->id_personne = $id;
    }
    
    public function setRoles($roles)
    {
        if(!is_string($roles))
        {
            $this->errors[] = self::INVALID_ROLES;
        }
        else
        {
            $this->roles[] = explode(",", $roles);
        }
    }
    
    /* *********** Méthodes ********** */
    public function hasRole($role)
    {
        if(!is_string($role))
        {
            throw new InvalidArgumentException("Le role doit être une chaine de caractère");
        }
        else
        {
            return in_array($role, $this->roles);
        }
    }
}