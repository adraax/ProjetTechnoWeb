<?php
namespace GJLMFramework;

class PasswordField extends Field
{
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
        $widget .= '<input type="password" id="'.$this->name.'" name="'.$this->name.'class="form-control" required="required"';
        
        if(!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars(strip_tags($this->value)).'"';
        }
        return $widget .= ' />';
    }
}