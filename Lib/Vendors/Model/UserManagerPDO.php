<?php
namespace Model;

use \Entity\User;

class UserManagerPDO extends UserManager
{
    public function add(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO user SET id_personne = :id_personne, username = :username, password = :password, roles = :roles');
    }
}