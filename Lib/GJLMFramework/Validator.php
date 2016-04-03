<?php
namespace GJLMFramework;

abstract class Validator
{
    /* *********** Propriétés *********** */
    protected $errorMessage;
    
    /* ********** Constructeur ********** */
    public function __construct($errorMessage)
    {
        $this->setErrorMessage($errorMessage);
    }
    
    /* ********** Getter ********* */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    public function setErrorMessage($errorMessage)
    {
        if(is_string($errorMessage))
        {
            $this->errorMessage = $errorMessage;
        }
    }
}