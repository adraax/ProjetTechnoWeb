<?php
namespace GJLMFramework;

/**
 * Permet d'hydrater les objets, en appelant automatiquement les setters
 */
trait Hydrator
{
    function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) 
        {
            $method = 'set'.ucfirst($key);
            
            if(is_callable([$this, $method]))
            {
                $this->$method($value);
            }
        }
    }
}
