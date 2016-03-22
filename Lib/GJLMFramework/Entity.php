<?php
namespace GJLMFramework;

abstract class Entity
{
    use Hydrator;
    
    /* ********** Propriétés ********** */
    protected $id;
    protected $errors = [];
    
    public function __construct(array $donnees = [])
    {
        if(!empty($donnees))
        {
            $this->hydrate($donnees);
        }
    }
    
    /* ********** Getter ********** */
    public function getId()
    {
        return $this->id;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    /* *********** Setter ********** */
    public function setId($id)
    {
        $this->id = (int) $id;   
    }
    
    /* *********** Méthodes ********** */
    public function isNew()
    {
        return !isset($this->id);
    }
}
