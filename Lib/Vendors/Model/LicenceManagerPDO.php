<?php
namespace Model;
use \Entity\Licence;
class LicenceManagerPDO extends LicenceManager
{
    public function getUnique($num)
    {
        $requete = $this->dao->prepare('SELECT num, id_personne, type, activated FROM licence WHERE num = :num');
        $requete->bindValue(':num', (int) $num, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Licence');
        
        if($licence = $requete->fetch())
        {
            return $licence;
        }
        
        return null;
    }
    
    public function getByPersonneId($id)
    {
        $requete = $this->dao->prepare('SELECT num, id_personne, type, activated FROM licence WHERE id_personne = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Licence');
         
        $listeLicences = $requete->fetchAll();
        
        return $listeLicences; 
    }
    
    protected function add(Licence $licence)
    {
        $requete = $this->dao->prepare('INSERT INTO licence SET num = :num, id_personne = :id, type = :type, activated = :activated');
        $requete->bindValue(':num', (int) $licence->getNum(), \PDO::PARAM_INT);
        $requete->bindValue(':id', (int) $licence->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':type', (string) $licence->getType(), \PDO::PARAM_STR);
        $requete->bindValue(':activated', (bool) $licence->getActivated(), \PDO::PARAM_BOOL);
        
        $requete->execute();
    }
    
    protected function modify(Licence $licence)
    {
        $requete = $this->dao->prepare('UPDATE licence SET id_personne = :id, type = :type, activated = :activated WHERE num = :num');
        $requete->bindValue(':num', (int) $licence->getNum(), \PDO::PARAM_INT);
        $requete->bindValue(':id', (int) $licence->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':type', (string) $licence->getType(), \PDO::PARAM_STR);
        $requete->bindValue(':activated', (bool) $licence->getActivated(), \PDO::PARAM_BOOL);
        
        $requete->execute();
    }
    
	public function existe($num)
	{
		$requete = $this->dao->prepare('SELECT * FROM licence WHERE num = :num');
		$requete->bindValue(':num', $num, \PDO::PARAM_INT);
		$requete->execute();
	   
		if(empty($requete->fetch()))
			return false;
		else
			return true;
	}
	
    public function delete($num)
    {
        $this->dao->exec('DELETE FROM licence WHERE num = '.(int) $num);
    }
}