<?php

namespace Model;

use \Entity\Competiteur;

class CompetiteurManagerPDO extends CompetiteurManager
{
	//Compétiteur = adherent dans la BD
	public function getUnique($id)
	{
		$requete = $this->dao->prepare('SELECT id, num_personne, categorie, specialite, objectif_saison, certif_med FROM adherent WHERE id = :id');
		$requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
		$requete->execute();
	   
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competiteur');
	   
		if($competiteur = $requete->fetch())
		{
			return $competiteur;
		}
	   
		return null;
	}
   
	protected function add(Competiteur $competiteur)
	{
		$requete = $this->dao->prepare('INSERT INTO adherent SET num_personne = :num_personne, categorie = :categorie, specialite = :specialite, objectif_saison = :objectif_saison, certif_med = :certif_med');
		$requete->bindValue(':num_personne', $competiteur->getNum_personne(), \PDO::PARAM_INT);
		$requete->bindValue(':categorie', $competiteur->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':specialite', $competiteur->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':objectif_saison', $competiteur->getObjectif_saison(), \PDO::PARAM_STR);
		if($competiteur->getCertif_med())
			$certif = 1;
		else
			$certif = 0;
		$requete->bindValue(':certif_med', $certif, \PDO::PARAM_INT);
	   
		$requete->execute();
	}
   
	protected function modify(Competiteur $competiteur)
	{
		$requete = $this->dao->prepare('UPDATE adherent SET num_personne = :num_personne, categorie = :categorie, specialite = :specialite, objectif_saison = :objectif_saison, certif_med = :certif_med WHERE id = :id');
		$requete->bindValue(':id', $competiteur->getId(), \PDO::PARAM_INT);
		$requete->bindValue(':num_personne', $competiteur->getNum_personne(), \PDO::PARAM_INT);
		$requete->bindValue(':categorie', $competiteur->getCategorie(), \PDO::PARAM_STR);
		$requete->bindValue(':specialite', $competiteur->getSpecialite(), \PDO::PARAM_STR);
		$requete->bindValue(':objectif_saison', $competiteur->getObjectif_saison(), \PDO::PARAM_STR);
		$requete->bindValue(':certif_med', $competiteur->getCertif_med(), \PDO::PARAM_INT);
	   
		$requete->execute();
	}
   
	public function delete($id)
	{
		$this->dao->exec('DELETE FROM adherent WHERE id = '.(int) $id);
	}
   
	public function getByPersonneId($id)
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent WHERE num_personne = :id');
		$requete->bindValue(':id',(int) $id, \PDO::PARAM_INT);
		$requete->execute();
		
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competiteur');
		
		if($competiteur = $requete->fetch())
		{
			return $competiteur;
		}
		
		return null;
	}
	
	public function getList()
	{
		$requete = $this->dao->prepare('SELECT * FROM adherent');		
		$requete->execute();  
       
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competiteur');  
		
		return $requete->fetchAll();
	}
	
	public function getListDispo($id_competition)  
    {  
		$requete = $this->dao->prepare('SELECT * FROM adherent WHERE id NOT IN(
			SELECT num_competiteur FROM adherent_equipage WHERE num_equipage IN(
			SELECT id FROM equipage WHERE id_competition = :id_competition))');
		$requete->bindValue(':id_competition',(int) $id_competition, \PDO::PARAM_INT);		
		$requete->execute();  
       
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competiteur');  
	
		return $requete->fetchAll();  
    }
	
	public function getSansCertif()
	{  
		$requete = $this->dao->prepare('SELECT * FROM adherent WHERE certif_med = 0');		
		$requete->execute();  
       
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competiteur');  
	
		return $requete->fetchAll();  
    }
}