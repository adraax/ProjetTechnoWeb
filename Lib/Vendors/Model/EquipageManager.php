<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Equipage;

abstract class EquipageManager extends Manager
{
    abstract protected function add(Equipage $equipage);
    abstract protected function modify(Equipage $equipage);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
	abstract public function getNonValides();
	
	abstract public function isParticipant($id_participant, $id_equipage);
	abstract public function addParticipant($id_participant, $id_equipage, $valide);
	abstract public function deleteParticipant($id_participant, $id_equipage);
	abstract public function participantValide($id_participant, $id_equipage);
	abstract public function nbParticipantsValides($id_equipage);
	abstract public function setParticipantValide($id_participant, $id_equipage);
	
	abstract public function getInvitesByCompetiteurId($id_competiteur);
	abstract public function isInvite($id_participant, $id_equipage);
	abstract public function addInvite($id_invite, $id_equipage);
	abstract public function deleteInvite($id_invite, $id_equipage);
	
    
    public function save(Equipage $equipage)
    {
        if($equipage->isValid())
        {
            if($equipage->isNew())
				return $this->add($equipage);
			else
				$this->modify($equipage);
        }
        else
        {
            throw new \RuntimeException("L\'équipage doit être valide pour être enregistré.");
        }
    }
}