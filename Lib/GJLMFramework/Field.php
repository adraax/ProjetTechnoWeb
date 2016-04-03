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
    protected $validators = [];
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
    
    public function getValidators()
    {
        return $this->validators;
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
    
    public function setValidators(array $validators)
    {
        foreach ($validators as $validator)
        {
            if($validator instanceof Validator && !in_array($validator, $this->validators))
            {
                $this->validators[] = $validator;
            }
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
        foreach ($this->validators as $validator)
        {
            if(!$validator->isValid($this->value))
            {
                $this->errorMessage = $validator->getErrorMessage();
                return false;
            }
        }
        return true;
    }
}