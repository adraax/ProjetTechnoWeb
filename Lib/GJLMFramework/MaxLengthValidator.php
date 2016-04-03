<?php
namespace GJLMFramework;

class MaxLengthValidator extends Validator
{
    /* *********** Propriétés ********** */
    protected $maxLength;
    
    /* ********** Constructeur ********* */
    public function __construct($errorMessage, $maxLength)
    {
        parent::__construct($errorMessage);
        
        $this->setMaxLength($maxLength);
    }
    
    /* *********** Setter ********** */
    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;
        
        if($maxLength > 0)
        {
            $this->maxLength = $maxLength;
        }
        else
        {
            throw new InvalidArgumentException("La taille maximale doit être strictement supérieure à 0.");
        }
    }
    
    /* *********** Méthode ********** */
    public function isValid($value)
    {
        return strlen($value) <= $this->maxLength;
    }
}