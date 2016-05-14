<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\User;

abstract class UserManager extends Manager
{
    abstract protected function add(User $user);
    abstract protected function modify(User $user);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
    abstract public function getByPersonneId($id);
    
    public function save(User $user)
    {
        if($user->isValid())
        {
            $user->isNew() ? $this->add($user) : $this->modify($user);
        }
        else
        {
            throw new \RuntimeException("L'utilisateur doit être valide pour être enregistrée.");
        }
    }
}