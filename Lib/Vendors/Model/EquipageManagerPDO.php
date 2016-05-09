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
			//Récupération des invités et des participants
			$requete = $this->dao->prepare('SELECT num_competiteur FROM adherent_equipage WHERE num_competition = :id');
			$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
			$requete->execute();
			
			$equipage->setParticipants($requete->fetchAll());
			
			$requete = $this->dao->prepare('SELECT id_competiteur FROM adherent_equipage_invite WHERE id_competition = :id');
			$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
			$requete->execute();
			
			$equipage->setInvites($requete->fetchAll());
			
			return $equipage;
		}
	   
		return null;
	}
       
	protected function add(Equipage $equipage)
	{
		$requete = $this->dao->prepare('INSERT INTO equipage SET specialite = :specialite, categorie = :categorie, nb_places = :nb_places, num_competition = :id_competition');
		$requete->bindValue(':specialite', $equipage->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':categorie', $equipage->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':nb_places', $equipage->getNb_places(), \PDO::PARAM_INT);
		$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
		
		$requete->execute();
		
		$this->majParticipantsInvites($equipage);
	}
	
	protected function majParticipantsInvites(Equipage $equipage)
	{
		//Ajout des participants
		$requete = $this->dao->prepare('SELECT num_competiteur FROM adherent_equipage WHERE num_equipage = :id_equipage');
		$requete->bindValue(':id_equipage', $equipage->getId(), \PDO::PARAM_INT);
		$requete->execute();
		$id_participants = $requete->fetchAll();
		foreach($equipage->getParticipants() as $participant)
		{
			if(!in_array($participant, $id_participants);
			{
				$requete = $this->dao->prepare('INSERT INTO adherent_equipage SET num_competiteur = :id_competiteur, num_competition = :id_competition');
				$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
				$requete->bindValue(':id_competiteur', $participant, \PDO::PARAM_INT);
				$requete->execute();
			}
		}
		//Suppression des participants
		foreach($id_participants as $participant)
		{
			if(!in_array($participant, $equipage->getParticipants());
			{
				$requete = $this->dao->prepare('DELETE FROM adherent_equipage WHERE num_competiteur = :id_competiteur AND num_competition = :id_competition');
				$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
				$requete->bindValue(':id_competiteur', $participant, \PDO::PARAM_INT);
				$requete->execute();
			}
		}
		
		//Ajout des invités
		$requete = $this->dao->prepare('SELECT id_competiteur FROM adherent_equipage_invite WHERE id_equipage = :id_equipage');
		$requete->bindValue(':id_equipage', $equipage->getId(), \PDO::PARAM_INT);
		$requete->execute();
		$id_invites = $requete->fetchAll();
		foreach($equipage->getInvites() as $invite)
		{
			if(!in_array($invite, $id_invites);
			{
				$requete = $this->dao->prepare('INSERT INTO adherent_equipage_invite SET id_competiteur = :id_competiteur, id_competition = :id_competition');
				$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
				$requete->bindValue(':id_competiteur', $participant, \PDO::PARAM_INT);
				$requete->execute();
			}
		}
		//Suppression des invites
		foreach($id_invites as $invite)
		{
			if(!in_array($invite, $equipage->getInvites());
			{
				$requete = $this->dao->prepare('DELETE FROM adherent_equipage_invite WHERE id_competiteur = :id_competiteur AND id_competition = :id_competition');
				$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
				$requete->bindValue(':id_competiteur', $invite, \PDO::PARAM_INT);
				$requete->execute();
			}
		}
	}
	
	protected function modify(Equipage $equipage)
	{
		$requete = $this->dao->prepare('UPDATE equipage SET specialite = :specialite, categorie = :categorie, nb_places = :nb_places, num_competition = :id_competition WHERE id = :id');
		$requete->bindValue(':id', $equipage->getId(), \PDO::PARAM_INT);
		$requete->bindValue(':specialite', $equipage->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':categorie', $equipage->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':nb_places', $equipage->getNb_places(), \PDO::PARAM_INT);
		$requete->bindValue(':id_competition', $equipage->getId_competition(), \PDO::PARAM_INT);
	   
		$requete->execute();
		
		$this->majParticipantsInvites($equipage);
	}
   
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM equipage WHERE id = '.(int) $id);
	}
}