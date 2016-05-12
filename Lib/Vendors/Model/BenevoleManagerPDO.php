<?php
namespace Model;
use \Entity\Benevole;
class BenevoleManagerPDO extends BenevoleManager
{
    protected function add(Benevole $benevole)
    {
        $requete = $this->dao->prepare('INSERT INTO accompagnateur_benevole SET id_personne = :id_personne, id_competition = :id_competition, role = :role');
        $requete->bindValue(':id_personne', $benevole->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':id_competition', $benevole->getId_competition(), \PDO::PARAM_INT);
        $requete->bindValue(':role', $benevole->getRole(), \PDO::PARAM_STR);
        
        $requete->execute();
    }
    
    protected function modify(Benevole $benevole)
    {
        $requete = $this->dao->prepare('UPDATE accompagnateur_benevole SET id_personne = :id_personne, id_competition = :id_competition, role = :role WHERE id = :id');
        $requete->bindValue(':id', $benevole->getId(), \PDO::PARAM_INT);
        $requete->bindValue(':id_personne', $benevole->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':id_competition', $benevole->getId_competition(), \PDO::PARAM_INT);
        $requete->bindValue(':role', $benevole->getRole(), \PDO::PARAM_STR);
        
        $requete->execute();
    }
    
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM accompagnateur_benevole WHERE id = '.(int) $id);
    }
    
    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM accompagnateur_benevole WHERE id = :id');
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Benevole');
        
        if($benevole = $requete->fetch())
        {
            return $benevole;
        }
        
        return null;
    }
	
	public function getByCompetition($id_competition)
	{
		$benevoles = [];  
       
		$requete = $this->dao->prepare('SELECT * FROM accompagnateur_benevole WHERE id_competition =  :id_competition');
		$requete->bindValue(':id_competition', $id_competition, \PDO::PARAM_INT);	
		$requete->execute();  
       
		$requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Benevole');  
		while($donnees = $requete->fetch())  
			$benevoles[] = $donnees;
	
		return $benevoles; 
	}
}