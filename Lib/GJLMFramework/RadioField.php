<?php
namespace GJLMFramework;

class RadioField extends Field
{
    /* ********** Propréiétes *********** */
	protected $boutons = [];
    
    /* ********** Setter ********** */
    public function addBouton($bouton)
    {
         if(is_string($bouton) && !empty($bouton))
         {
             $this->boutons[] = $bouton;
         }
         else
         {
             throw new \InvalidArgumentException("Le nom du bouton doit être du texte.");
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
		foreach($this->boutons as $bouton)
		{
			$widget .= '<input type="radio" id="'.$bouton.'" name="'.$this->name.'"';
			$widget .= ' value="'.htmlspecialchars(strip_tags($bouton)).'"';
			
			if(!empty($this->value) && $this->value==$bouton)
				$widget .= ' checked="checked"';
			
			$widget .= ' /><label for="'.$bouton.'">'.$bouton.'</label><br />';
        }
        
        return $widget;
    }
}