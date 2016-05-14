<?php
namespace Model;
use \Entity\User;
class UserManagerPDO extends UserManager
{
    protected function add(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO user SET id_personne = :id_personne, username = :username, password = :password, roles = :roles');
        $requete->bindValue(':id_personne', (int) $user->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':username', $user->getUsername(), \PDO::PARAM_STR);
        $requete->bindValue(':password', password_hash($user->getPassword(), PASSWORD_DEFAULT) , \PDO::PARAM_STR);
        $requete->bindValue(':roles', $user->getRoles(), \PDO::PARAM_STR);
        
        $requete->execute();
    }
    
    protected function modify(User $user)
    {
        $requete = $this->dao->prepare('UPDATE user SET id_personne = :id_personne, username = :username, password = :password, roles = :roles WHERE id = :id');
        $requete->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
        $requete->bindValue(':id_personne', $user->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':username', $user->getUsername(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
        $requete->bindValue(':roles', $user->getRoles(), \PDO::PARAM_STR);
        
        $requete->execute();
    }
    
    public function delete($id)
    {
        $this->dao->exec('DELETE FROM user WHERE id = '.(int) $id);
    }
    
    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM user WHERE id = :id');
        $requete->bindValue(':id', $id, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');
        
        if($user = $requete->fetch())
        {
            return $user;
        }
        
        return null;
    }
    
    public function getByPersonneId($id)
    {
        $requete = $this->dao->prepare('SELECT id, username FROM user WHERE id_personne = :id');
        $requete->bindValue(':id',(int) $id, \PDO::PARAM_INT);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');
        
         if($user = $requete->fetch())
        {
            return $user;
        }
        
        return null;
    }
    
    public function getByName($name)
    {
        $requete = $this->dao->prepare('SELECT id, username, password, roles FROM user WHERE username = :name');
        
        $requete->bindValue(':name', (string) $name, \PDO::PARAM_STR);
        $requete->execute();
        
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');
        
        if($user = $requete->fetch())
        {
            return $user;
        }
        
        return null;
    }
	
	//Permet de retourner tous les utilisateurs  
	public function getList()  
    {  
      $users = [];  
       
      $requete = $this->dao->prepare('SELECT id, id_personne, username, password, roles FROM user');  
      $requete->execute();  
       
      $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');  
      while($donnees = $requete->fetch())  
        $users[] = $donnees;  
      return $users;  
    } 
}