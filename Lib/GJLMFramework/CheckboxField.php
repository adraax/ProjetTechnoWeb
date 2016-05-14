<?php
namespace GJLMFramework;

class CheckboxField extends Field
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
        
        $widget .= '<p>'.$this->label.'<br />';
		foreach($this->boutons as $bouton)
		{
			$widget .= '<input type="checkbox" id="'.$bouton.'" name="'.$this->name.'[]" value="'.$bouton.'"';
			
			if(!empty($this->value) && substr_count($this->value, $bouton)>0)
				$widget .= ' checked="checked"';
			
			$widget .= ' /><label for="'.$bouton.'">'.$bouton.'</label><br />';
        }
		$widget .= '</p>';
        
        return $widget;
    }
}