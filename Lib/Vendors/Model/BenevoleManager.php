<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Benevole;

abstract class BenevoleManager extends Manager
{
    abstract protected function add(Benevole $benevole);
    abstract protected function modify(Benevole $benevole);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
    abstract public function getByCompetition($id_competition);
    
    public function save(Benevole $benevole)
    {
        if($benevole->isValid())
        {
            $benevole->isNew() ? $this->add($benevole) : $this->modify($benevole);
        }
        else
        {
            throw new \RuntimeException("Le bénévole doit être valide pour être enregistrée.");
        }
    }
}