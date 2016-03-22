<?php
namespace GJLMFramework;

abstract class Field
{
    //on utilise Hydrator pour hydrater les champs
    use Hydrator;
    
    /* ********** Propriétés ********** */
    protected $errorMessage;
    protected $label;
    protected $name;
    protected $value;
    
    /* *********** Constructeur *********** */
    public function __construct(array $options = [])
    {
        if(!empty($options))
        {
            $this->hydrate($options);
        }
    }
    
    /* ********** Getter *********** */
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    /* ********** Setter *********** */
    public function setLabel($label)
    {
        if(is_string($label))
        {
           $this->label = $label; 
        }
    }
    
    public function setName($name)
    {
        if(is_string($name))
        {
            $this->name = $name;
        }
    }
    
    public function setValue($value)
    {
        if(is_string($value))
        {
            $this->value = $value;
        }
    }
    
    /* ********** Méthodes ********** */
    abstract public function buildWidget(); //permet de créer le champ du formulaire
    
    public function isValid()
    {
        //TODO 
    }
}