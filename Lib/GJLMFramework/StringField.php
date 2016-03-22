<?php
namespace GJLMFramework;

class StringField extends Field
{
    /* ********** Propréiétes *********** */
    protected $maxLength;
    
    /* ********** Setter ********** */
    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;
         if($amxLength > 0)
         {
             $this->maxLength = $maxLength;
         }
         else
         {
             throw new \InvalidArgumentException("La taille maximale doit être supérieure à 0.");
         }
    }
    
    /* ********** Méthodes ********** */
    public function buildWidget()
    {
        
    }
}