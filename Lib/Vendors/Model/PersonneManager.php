<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Personne;

abstract class PersonneManager extends Manager
{
    abstract protected function add(Personne $personne);
    abstract protected function modify(Personne $personne);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
	abstract public function getListSansLicence();
    
    public function save(Personne $personne)
    {
        if($personne->isValid())
        {
            $personne->isNew() ? $this->add($personne) : $this->modify($personne);
        }
        else
        {
            throw new \RuntimeException("La personne doit être valide pour être enregistrée.");
        }
    }
}