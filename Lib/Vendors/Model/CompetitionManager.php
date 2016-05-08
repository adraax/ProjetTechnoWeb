<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Competition;

abstract class CompetitionManager extends Manager
{
    abstract protected function add(Competition $competition);
    abstract protected function modify(Competition $competition);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
	abstract public function getList();
	abstract public function getListByNiveau($niveau);
    
    public function save(Competition $competition)
    {
        if($competition->isValid())
        {
            $competition->isNew() ? $this->add($competition) : $this->modify($competition);
        }
        else
        {
            throw new \RuntimeException("La competition doit être valide pour être enregistrée.");
        }
    }
}