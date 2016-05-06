<?php
namespace Entity;

use \GJLMFramework\Entity;

class Equipage extends Entity
{
    /* ********** Propriétés ********** */
    protected $specialite,
                $categorie,
                $nb_places,
				$participants, //Tableau de compétiteurs participants
				$invites, //Tableau de compétiteurs invités
				$competition; //Compétition à laquelle l'équipage participe
                
    const SPECIALITE_INV = 1;
    const CATEGORIE_INV = 2;
    const NB_PLACES_INV = 3;
	const PARTICIPANTS_INV = 4;
	const INVITES_INV = 5;
	const COMPETITION_INV = 6;
    
    /* *********** Getter ********** */
    public function getSpecialite()
    {
        return $this->specialite;
    }
    
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    public function getNb_places()
    {
        return $this->nb_places;
    }
	
	public function getParticipants()
    {
        return $this->participants;
    }
	
	public function getInvites()
	{
		return $this->invites;
	}
	
	public function getCompetition()
	{
		return $this->competition;
	}
    
    /* ********** Setter ********** */
    public function setSpecialite($specialite)
    {
        if ($specialite != 'kayak' && $specialite != 'canoe')
        {
            $this->errors[] = self::SPECIALITE_INV;
        }
        
        $this->specialite = $specialite;
    }
    
    public function setCategorie($categorie)
    {
        if($categorie != 'Veteran' && $categorie != 'Senior' && $categorie != 'Junior' && $categorie != 'Cadet' && $categorie != 'Minime')
        {
            $this->errors[] = self::CATEGORIE_INV;
        }
        
        $this->categorie = $categorie;
    }
    
    public function setNb_places($nb_places)
    {
        if(!is_int($nb_places) || empty($nb_places))
        {
            $this->errors[] = self::NB_PLACES_INV;
        }
        
        $this->nb_places = $nb_places;
    }
	
	public function setParticipants($participants)
    {
        if(!is_array($participants) || empty($participants))
        {
            $this->errors[] = self::PARTICIPANTS_INV;
        }
        
        $this->partcipants = $participants;
    }
	
	public function setInvites($invites)
    {
        if(!is_array($invites) || empty($invites))
        {
            $this->errors[] = self::INVITES_INV;
        }
        
        $this->invites = $invites;
    }
	
	public function setCompetition($competition)
	{
		if(get_class($competion) != 'Competition' || empty($competition))
		{
			$this->errors[] = self::COMPETITION_INV;
		}
		$this->competition = $competition;
	}
}