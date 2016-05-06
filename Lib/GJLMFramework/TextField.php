<?php
namespace GJLMFramework;

class TextField extends Field
{
    /* ********** Propriétés ********** */
    protected $cols;
    protected $rows;
    
    /* ********** Setter ********** */
    public function setCols($cols)
    {
        $cols = (int) $cols;
        
        if($cols > 0)
        {
            $this->cols = cols;
        }
        else
        {
            throw new InvalidArgumentException("Le nombre de colonnes doit être supérieur à 0.");            
        }
    }
    
    public function setRows($rows)
    {
        $rows = (int) $rows;
        
        if($rows > 0)
        {
            $this->rows = rows;
        }
        else
        {
            throw new InvalidArgumentException("Le nombre de lignes doit être supérieur à 0.");            
        }
    }
    
    /* *********** Méthodes *********** */
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
        $widget .= '<textarea id="'.$this->name.'" name="'.$this->name.'" class="form-control"';
        
        if(!empty($this->cols))
        {
            $widget .= ' cols="'.$this->cols.'"';
        }
        
        if(!empty($this->rows))
        {
            $widget .= ' rows="'.$this->rows.'"';
        }
        
        $widget .= '>';
        
        if(!empty($this->value))
        {
            $widget .= htmlspecialchars(strip_tags($this->value));
        }
        
        return $widget .= '</textarea>';
    }
}