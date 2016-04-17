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
       
       protected function add(Personne $personne)
       {
           $requete = $this->dao->prepare('INSERT INTO personne SET nom = :nom, prenom = :prenom, num_tel = :num_tel, email = :email, adresse = :adresse, date_naissance = :date_naissance, sexe = :sexe');
           $requete->bindValue(':nom', $personne->getNom(), \PDO::PARAM_STR);
           $requete->bindValue(':prenom', $personne->getPrenom(), \PDO::PARAM_STR);
           $requete->bindValue(':num_tel', $personne->getTel(), \PDO::PARAM_INT);
           $requete->bindValue(':email', $personne->getEmail(), \PDO::PARAM_STR);
           $requete->bindValue(':adresse', $personne->getAdresse(), \PDO::PARAM_STR);
           $requete->bindValue(':date_naissance', $personne->getDate_naissance());
           $requete->bindValue(':sexe', $personne->getSexe(), \PDO::PARAM_STR);
           
           $requete->execute();
       }
       
       protected function modify(Personne $personne)
       {
           $requete = $this->dao->prepare('UPDATE personne SET nom = :nom, prenom = :prenom, num_tel = :num_tel, email = :email, adresse = :adresse, date_naissance = :date_naissance, sexe = :sexe WHERE id = :id');
           $requete->bindValue(':id', $personne->getId(), \PDO::PARAM_INT);
           $requete->bindValue(':nom', $personne->getNom(), \PDO::PARAM_STR);
           $requete->bindValue(':prenom', $personne->getPrenom(), \PDO::PARAM_STR);
           $requete->bindValue(':num_tel', $personne->getTel(), \PDO::PARAM_INT);
           $requete->bindValue(':email', $personne->getEmail(), \PDO::PARAM_STR);
           $requete->bindValue(':adresse', $personne->getAdresse(), \PDO::PARAM_STR);
           $requete->bindValue(':date_naissance', $personne->getDate_naissance());
           $requete->bindValue(':sexe', $personne->getSexe(), \PDO::PARAM_STR);
           
           $requete->execute();
       }
       
       public function delete($id)
       {
           $this->dao->exec('DELETE FROM personne WHERE id = '.(int) $id);
       }
}