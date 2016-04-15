<?php

namespace Model;

use \Entity\Personne;

class PersonneManagerPDO extends PersonneManager
{
       public function getUnique($id)
       {
           $requete = $this->dao->prepare('SELECT nom, prenom FROM personne WHERE id = :id');
           $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
           $requete->execute();
           
           $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Personne');
           
           if($personne = $requete->fetch())
           {
               return $personne;
           }
           
           return null;
       }
}