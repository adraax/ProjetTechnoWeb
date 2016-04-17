<?php
namespace Entity;

use \GJLMFramework\Entity;

class Licence extends Entity
{
    /* ********** Propriétés ********** */
    protected $num,
                $type,
                $id_personne,
                $activated,
                $date; //stockage temporaire pour inscription
                
    const NUM_INV = 1;
    const TYPE_INV = 2;
    const ID_PERSONNE_INV = 3;
    const ACTIVATED_INV = 4;
    
    /* *********** Getter ********** */
    public function getNum()
    {
        return $this->num;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getId_personne()
    {
        return $this->id_personne;
    }
    
    public function getActivated()
    {
        return $this->activated;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    /* ********** Setter ********** */
    public function setNum($num)
    {
        if (!is_string($num) || empty($num))
        {
            $this->errors[] = self::NUM_INV;
        }
        
        $this->num = $num;
    }
    
    public function settype($type)
    {
        if(!is_string($type) || empty($type))
        {
            $this->errors[] = self::TYPE_INV;
        }
        
        $this->type = $type;
    }
    
    public function setId_personne($id)
    {
        if(!is_int($id) || empty($id))
        {
            $this->errors[] = self::ID_PERSONNE_INV;
        }
        
        $this->id_personne = $id;
    }
    
    public function setActivated($bool)
    {
        $bool = (int) $bool;
        
        if($bool <= 0)
        {
            $this->errors[] = self::ACTIVATED_INV;
        }
        
        if($bool === 0)
        {
            $this->activated = false;
        }
        else if($bool >0)
        {
            $this->activated = true;
        }
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
}