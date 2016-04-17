<?php
namespace Model;

use \Entity\User;

class UserManagerPDO extends UserManager
{
    protected function add(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO user SET id_personne = :id_personne, username = :username, password = :password, roles = :roles');
        $requete->bindValue(':id_personne', $user->getId_personne(), \PDO::PARAM_INT);
        $requete->bindValue(':username', $user->getUsername(), \PDO::PARAM_STR);
        $requete->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
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
        $this->dao-exec('DELETE FROM user WHERE id = '.(int) $id);
    }
}