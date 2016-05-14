<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Licence;

abstract class LicenceManager extends Manager
{
    abstract protected function add(Licence $licence);
    abstract protected function modify(Licence $licence);
    abstract public function delete($num);
    
    abstract public function getByPersonneId($id);
    abstract public function getUnique($num);
    
    public function save(Licence $licence)
    {
        if($licence->isValid())
        {
            $licence->isNew() ? $this->add($licence) : $this->modify($licence);
        }
        else
        {
            throw new \RuntimeException("La licence doit être valide pour être enregistrée.");
        }
    }
    
}