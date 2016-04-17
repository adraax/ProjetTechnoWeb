<?php
namespace GJLMFramework;

class DatePickerField extends Field
{
    public function buildWidget()
    {
        $class = "";
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
        $widget .= '<input type="date" id="'.$this->name.'" name="'.$this->name.'" class="form-control"';
        
        if(!empty($this->value))
        {
            $widget .= ' value="'.htmlspecialchars(strip_tags($this->value)).'"';
        }
        return $widget .= ' /></div>';
    }
}