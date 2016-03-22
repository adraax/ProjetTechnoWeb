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
    // C'est sale et moche, mais ça marche
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
        $widget .= '<input type="text" id="'.$this->label.'" name="'.$this->label.'class="form-control" required="required"';
        
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