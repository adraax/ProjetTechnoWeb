<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Equipage;

abstract class EquipageManager extends Manager
{
    abstract protected function add(Equipage $equipage);
	abstract protected function majParticipantsInvites(Equipage $equipage)
    abstract protected function modify(Equipage $equipage);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
    
    public function save(Equipage $equipage)
    {
        if($equipage->isValid())
        {
            $equipage->isNew() ? $this->add($equipage) : $this->modify($equipage);
        }
        else
        {
            throw new \RuntimeException("L\'équipage doit être valide pour être enregistré.");
        }
    }
}