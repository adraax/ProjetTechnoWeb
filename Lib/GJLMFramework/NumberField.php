<?php
namespace GJLMFramework;

class NumberField extends Field
{
    /* ********** Propréiétes *********** */
    protected $maxLength;
    
    /* ********** Setter ********** */
    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;
         if($maxLength > 0)
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
        if(!empty($this->errorMessage))
        {
            $class = "has-error";
            $widget = '<div class="form-group '.$class.'">';
            $widget .= "<span class='help-block'>".$this->errorMessage."</span>";
        }
        else
        {
            $class = "";
            $widget = '<div class="form-group '.$class.'">';
        }
        
        $widget .= '<label for="'.$this->name.'">'.$this->label.'</label>';
        $widget .= '<input type="number" id="'.$this->name.'" name="'.$this->name.'" class="form-control"';
        
        if(!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars(strip_tags($this->value)).'"';
        }
        
        if(!empty($this->maxLength))
        {
            $widget .= ' maxlength="'.$this->maxLength.'"';
        }
        
        return $widget .= ' />';
    }
}