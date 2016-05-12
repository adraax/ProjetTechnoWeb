<?php
namespace Model;

use \Entity\Equipage;

class EquipageManagerPDO extends EquipageManager
{
    public function getUnique($id)
    {
		$requete = $this->dao->prepare('SELECT * FROM equipage WHERE id = :id');
		$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$requete->execute();
	   
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Equipage');
	   
		if($equipage = $requete->fetch())
		{
			$requete->setFetchMode(\PDO::FETCH_NUM);
			
			//Récupération des invités et des participants
			$requete = $this->dao->prepare('SELECT num_competiteur FROM adherent_equipage WHERE num_equipage = :id');
			$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
			$requete->execute();
			
			while($donnees = $requete->fetch())
			{
				$equipage->addParticipant((int)$donnees[0]);
			}
			
			$requete = $this->dao->prepare('SELECT id_competiteur FROM adherent_equipage_invite WHERE id_equipage = :id');
			$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
			$requete->execute();
			
			while($donnees = $requete->fetch())
			{
				$equipage->addInvite((int)$donnees[0]);
			}
			
			return $equipage;
		}

		return null;
	}
       
	protected function add(Equipage $equipage)
	{
		$requete = $this->dao->prepare('INSERT INTO equipage SET specialite = :specialite, categorie = :categorie, nb_places = :nb_places, id_competition = :id_competition');
		$requete->bindValue(':specialite', $equipage->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':categorie', $equipage->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':nb_places', $equipage->getNb_places(), \PDO::PARAM_INT);
		$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
		
		$requete->execute();

		$lastid = $this->dao->lastInsertId();

		return (int)$lastid;
	}
	
	public function participantValide($id_participant, $id_equipage)
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent_equipage WHERE num_competiteur = :id_competiteur AND 
			num_equipage = :id_equipage AND valide = 1');
		$requete->bindValue(':id_competiteur', $id_participant, \PDO::PARAM_INT);
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->execute();
	   
		if(empty($requete->fetch()))
			return false;
		else
			return true;
	}
	
	public function nbParticipantsValides($id_equipage)
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent_equipage WHERE num_equipage = :id_equipage AND valide = 1');
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->execute();
		
		return count($requete->fetchAll());
	}
	
	public function isParticipant($id_participant, $id_equipage)
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent_equipage WHERE num_competiteur = :id_competiteur AND 
			num_equipage = :id_equipage');
		$requete->bindValue(':id_competiteur', $id_participant, \PDO::PARAM_INT);
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->execute();
	   
		if(empty($requete->fetch()))
			return false;
		else
			return true;
	}
	
	public function addParticipant($id_participant, $id_equipage, $valide)
	{
		$requete = $this->dao->prepare('INSERT INTO adherent_equipage SET num_competiteur = :id_competiteur, num_equipage = :id_equipage, valide = :valide');
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->bindValue(':id_competiteur', $id_participant, \PDO::PARAM_INT);
		if($valide)
			$requete->bindValue(':valide', 1, \PDO::PARAM_INT);
		else
			$requete->bindValue(':valide', 0, \PDO::PARAM_INT);
		
		$requete->execute();
	}
	
	public function deleteParticipant($id_participant, $id_equipage)
	{
		$requete = $this->dao->prepare('DELETE FROM adherent_equipage WHERE num_competiteur = :id_competiteur AND num_equipage = :id_equipage');
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->bindValue(':id_competiteur', $id_participant, \PDO::PARAM_INT);
		$requete->execute();
	}
	
	public function isInvite($id_participant, $id_equipage)
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent_equipage_invite WHERE id_competiteur = :id_competiteur AND 
			id_equipage = :id_equipage');
		$requete->bindValue(':id_competiteur', $id_participant, \PDO::PARAM_INT);
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->execute();
	   
		if(empty($requete->fetch()))
			return false;
		else
			return true;
	}
	
	public function addInvite($id_invite, $id_equipage)
	{
		$requete = $this->dao->prepare('INSERT INTO adherent_equipage_invite SET id_competiteur = :id_competiteur, id_equipage = :id_equipage');
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->bindValue(':id_competiteur', $id_invite, \PDO::PARAM_INT);
		
		$requete->execute();
	}
	
	public function deleteInvite($id_invite, $id_equipage)
	{
		$requete = $this->dao->prepare('DELETE FROM adherent_equipage_invite WHERE id_competiteur = :id_competiteur AND id_equipage = :id_equipage');
		$requete->bindValue(':id_equipage', $id_equipage, \PDO::PARAM_INT);
		$requete->bindValue(':id_competiteur', $id_invite, \PDO::PARAM_INT);
		$requete->execute();
	}
	
	protected function modify(Equipage $equipage)
	{
		$requete = $this->dao->prepare('UPDATE equipage SET specialite = :specialite, categorie = :categorie, nb_places = :nb_places, id_competition = :id_competition WHERE id = :id');
		$requete->bindValue(':id', $equipage->getId(), \PDO::PARAM_INT);
		$requete->bindValue(':specialite', $equipage->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':categorie', $equipage->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':nb_places', $equipage->getNb_places(), \PDO::PARAM_INT);
		$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
	   
		$requete->execute();
	}
   
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM equipage WHERE id = '.(int) $id);
	}
}