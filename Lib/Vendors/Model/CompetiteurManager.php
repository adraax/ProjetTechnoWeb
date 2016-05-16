<?php
namespace Model;

use \GJLMFramework\Manager;
use \Entity\Competiteur;

abstract class CompetiteurManager extends Manager
{
    abstract protected function add(Competiteur $competiteur);
    abstract protected function modify(Competiteur $competiteur);
    abstract public function delete($id);
    
    abstract public function getUnique($id);
	abstract public function getByPersonneId($id);
	abstract public function getList();
	abstract public function getListDispo($id_competition);
	abstract public function getSansCertif();
    
    public function save(Competiteur $competiteur)
    {
        if($competiteur->isValid())
        {
            $competiteur->isNew() ? $this->add($competiteur) : $this->modify($competiteur);
        }
        else
        {
            throw new \RuntimeException("Le competiteur doit être valide pour être enregistré.");
        }
    }
}