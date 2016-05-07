<?php
namespace Model;

use \Entity\Competition;

class CompetiteurManagerPDO extends CompetitionManager
{
       public function getUnique($id)
       {
           $requete = $this->dao->prepare('SELECT id, niveau, adresse, code_postal, ville, meteo, type_hebergement, mode_transport, club_organisateur FROM competition WHERE id = :id');
           $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
           $requete->execute();
           
           $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Competition');
           
           if($competiteur = $requete->fetch())
           {
               return $competition;
           }
           
           return null;
       }
       
       protected function add(Competition $competition)
       {
           $requete = $this->dao->prepare('INSERT INTO competition SET niveau = :niveau, adresse = :adresse, code_postal = :code_postal, ville = :ville, 
		   meteo = :meteo, type_hebergement = :type_hebergement, mode_transport = :mode_transport, club_organisateur = :club_organisateur');
           $requete->bindValue(':niveau', $competition->getNiveau(), \PDO::PARAM_STR);
           $requete->bindValue(':adresse', $competition->getAdresse(), \PDO::PARAM_STR);
           $requete->bindValue(':code_postal', $competition->getCode_postal(), \PDO::PARAM_INT);
           $requete->bindValue(':ville', $competition->getVille(), \PDO::PARAM_STR);
           $requete->bindValue(':meteo', $competition->getMeteo(), \PDO::PARAM_STR);
		   $requete->bindValue(':type_hebergement', $competition->getType_hebergement(), \PDO::PARAM_STR);
		   $requete->bindValue(':mode_transport', $competition->getMode_transport(), \PDO::PARAM_STR);
		   $requete->bindValue(':club_organisateur', $competition->getClub_organisateur(), \PDO::PARAM_STR);
           
           $requete->execute();
       }
       
       protected function modify(Competition $competition)
       {
           $requete = $this->dao->prepare('UPDATE adherent SET niveau = :niveau, adresse = :adresse, code_postal = :code_postal, ville = :ville, 
		   meteo = :meteo, type_hebergement = :type_hebergement, mode_transport = :mode_transport, club_organisateur = :club_organisateur WHERE id = :id');
           $requete->bindValue(':id', $competition->getId(), \PDO::PARAM_INT);
           $requete->bindValue(':niveau', $competition->getNiveau(), \PDO::PARAM_STR);
           $requete->bindValue(':adresse', $competition->getAdresse(), \PDO::PARAM_STR);
           $requete->bindValue(':code_postal', $competition->getCode_postal(), \PDO::PARAM_INT);
           $requete->bindValue(':ville', $competition->getVille(), \PDO::PARAM_STR);
           $requete->bindValue(':meteo', $competition->getMeteo(), \PDO::PARAM_STR);
		   $requete->bindValue(':type_hebergement', $competition->getType_hebergement(), \PDO::PARAM_STR);
		   $requete->bindValue(':mode_transport', $competition->getMode_transport(), \PDO::PARAM_STR);
		   $requete->bindValue(':club_organisateur', $competition->getClub_organisateur(), \PDO::PARAM_STR);
           
           $requete->execute();
       }
       
       public function delete($id)
       {
           $this->dao->exec('DELETE FROM competition WHERE id = '.(int) $id);
       }
}