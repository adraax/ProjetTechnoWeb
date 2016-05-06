<?php
namespace GJLMFramework;

class ListField extends Field
{
    /* ********** Propréiétes *********** */
	protected $options = [];
    
    /* ********** Setter ********** */
    public function addOption($option, $nom)
    {
         if(is_int($option) && !empty($option) && is_string($nom) && !empty($nom))
         {
             $this->options[$option] = $nom;
         }
         else
         {
             throw new \InvalidArgumentException("Le nom de l'option doit être du texte et son numéro un nombre.");
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
        
        $widget .= '<label for="'.$this->name.'">'.$this->label.'</label><br />';
		$widget .= '<select name="'.$this->name.'" id ="'.$this->name.' class="form-control">';
		foreach($this->options as $option => $nom)
		{
			$widget .= '<option';
			$widget .= ' value="'.htmlspecialchars(strip_tags($option)).'"';
			
			if(!empty($this->value) && $this->value==$option)
				$widget .= ' selected';
			
			$widget .= '>'.$nom.'</option>';
        }
        $widget .= '</select>';
		
        return $widget;
    }
}