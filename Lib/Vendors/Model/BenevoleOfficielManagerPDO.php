<?php

namespace Model;

use \Entity\BenevoleOfficiel;

class BenevoleOfficielManagerPDO extends BenevoleOfficielManager
{
    protected function add(BenevoleOfficiel $benevole)
    {
        $requete = $this->dao->prepare('INSERT INTO accompagnateur_officiel SET id_licence = :id_licence, id_competition = :id_competition, role = :role');
        $requete->bindValue(':id_licence', $benevole->getId_licence(), \PDO::PARAM_INT);
        $requete->bindValue(':id_competition', $benevole->getId_competition(), \PDO::PARAM_INT);
        $requete->bindValue(':role', $benevole->getRole(), \PDO::PARAM_STR);

        $requete->execute();
    }
    
    protected function modify(BenevoleOfficiel $benevole)
    {
        $requete = $this->dao->prepare('UPDATE accompagnateur_officiel SET id_licence = :id_licence, id_competition = :id_competition, role = :role WHERE id = :id');
        $requete->bindValue(':id', $benevole->getId(), \PDO::PARAM_INT);
        $requete->bindValue(':id_licence', $benevole->getId_licence(), \PDO::PARAM_INT);
        $requete->bindValue(':id_competition', $benevole->getId_competition(), \PDO::PARAM_INT);
        $requete->bindValue(':role', $benevole->getRole(), \PDO::PARAM_STR);
        
        $requete->execute();
    }
    
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM accompagnateur_officiel WHERE id = '.(int) $id);
    }
    
    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM accompagnateur_officiel WHERE id = :id');
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\BenevoleOfficiel');
        
        if($benevole = $requete->fetch())
        {
            return $benevole;
        }
        
        return null;
    }
	
	public function getByCompetition($id_competition)
	{
		$benevoles = [];  
       
		$requete = $this->dao->prepare('SELECT * FROM accompagnateur_officiel WHERE id_competition =  :id_competition');
		$requete->bindValue(':id_competition', $id_competition, \PDO::PARAM_INT);	
		$requete->execute();  
       
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\BenevoleOfficiel');  
		while($donnees = $requete->fetch())  
			$benevoles[] = $donnees;
	
		return $benevoles; 
	}
	
	public function isBenevole($id_licence, $id_competition)
	{
		$requete = $this->dao->prepare('SELECT * FROM accompagnateur_officiel WHERE id_licence = :id_licence AND id_competition = :id_competition');
		$requete->bindValue(':id_licence', $id_licence, \PDO::PARAM_INT);
		$requete->bindValue(':id_competition', $id_competition, \PDO::PARAM_INT);
		$requete->execute();
	   
		if(empty($requete->fetch()))
			return false;
		else
			return true;
	}
}