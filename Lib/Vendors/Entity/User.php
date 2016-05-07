<?php
namespace Entity;

use \GJLMFramework\Entity;

class User extends Entity
{
    /* ********** Propriétés ********** */
    protected $username,
                $password,
                $confirm_password,
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
    
    public function getConfirm_password()
    {
        return $this->confirm_password;
    }
    
    public function getRoles()
    {
		if(is_string($this->roles))
			return $this->roles;
		else
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
    
    public function setConfirm_password($password)
    {
        $this->confirm_password = $password;
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
			if(empty($roles))
				$this->roles = [];
			else
				$this->roles = explode(",", $roles);
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
	
	public function addRole($role)
    {
        if(!is_string($role))
        {
            throw new InvalidArgumentException("Le role doit être une chaine de caractère");
        }
        else
        {
			if(!empty($role))
			{
				if(substr_count($this->getRoles(), $role)==0)
				{
					if(is_array($this->roles) && !in_array($role, $this->roles))
						$this->roles[] = $role;
					else
						if(!empty($this->roles))
							$this->setRoles($this->getRoles().','.$role);
						else
							$this->setRoles($role);
				}
			}
        }  
    }  

    public function isValid()  
    {  
        return !(empty($this->username) || empty($this->password) || ($this->password !== $this->confirm_password));  
    } 
}