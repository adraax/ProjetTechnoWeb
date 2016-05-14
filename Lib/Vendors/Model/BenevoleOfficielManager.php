<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\BenevoleOfficiel;

abstract class BenevoleOfficielManager extends Manager
{
    abstract protected function add(BenevoleOfficiel $benevole);
    abstract protected function modify(BenevoleOfficiel $benevole);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
    abstract public function getByCompetition($id_competition);
	abstract public function isBenevole($id_licence, $id_competition);
    
    public function save(BenevoleOfficiel $benevole)
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