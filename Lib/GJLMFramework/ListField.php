<?php
namespace GJLMFramework;

class ListField extends Field
{
    /* ********** Propréiétes *********** */
	protected $options = [];
	protected $onchange; //Pour pouvoir rajouter un script
    
    /* ********** Setter ********** */
    public function addOption($option, $nom)
    {
         if(!empty($option) && !empty($nom))
         {
             $this->options[$option] = $nom;
         }
         else
         {
             throw new \InvalidArgumentException("L'option ne peut pas être vide.");
         }
    }
    
	public function setOnchange($onchange)
	{
		if(is_string($onchange))
		{
			$this->onchange = $onchange;
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
		$widget .= '<select name="'.$this->name.'" id ="'.$this->name.'" ';
		
		if(!empty($this->onchange))
			$widget .= 'onchange="'.$this->onchange.'" ';
		
		$widget .= 'class="form-control">';
		foreach($this->options as $option => $nom)
		{
			$widget .= '<option';
			$widget .= ' value="'.htmlspecialchars(strip_tags($option)).'"';
			
			if(!empty($this->value) && $this->value==$option)
				$widget .= ' selected';
			
			$widget .= '>'.$nom.'</option>';
        }
        $widget .= '</select><br />';
		
        return $widget;
    }
}