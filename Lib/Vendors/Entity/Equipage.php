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
				$id_competition; //Compétition à laquelle l'équipage participe
                
    const SPECIALITE_INV = 1;
    const CATEGORIE_INV = 2;
    const NB_PLACES_INV = 3;
	const PARTICIPANTS_INV = 4;
	const INVITES_INV = 5;
	const ID_COMPETITION_INV = 6;
    
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
	
	public function getNb_participants()
	{
		return count($this->participants);
	}
	
	public function getInvites()
	{
		return $this->invites;
	}
	
	public function getId_competition()
	{
		return $this->id_competition;
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
        if($categorie != 'veteran' && $categorie != 'senior' && $categorie != 'junior' && $categorie != 'cadet' && $categorie != 'minime')
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
	
	public function setId_competition($id_competition)
	{
		if(!is_int($id_competition) || empty($id_competition))
		{
			$this->errors[] = self::ID_COMPETITION_INV;
		}
		$this->id_competition = $id_competition;
	}
	
	public function addParticipant($participant)
	{
		if(is_int($participant))
		{
			$this->participants[] = $participant;
		}
	}
	
	public function addInvite($invite)
	{
		if(is_int($invite))
		{
			$this->invites[] = $invite;
		}
	}
	
	public function isValid()
	{
		return !(empty($this->id_competition) || empty($this->nb_places) || empty($this->specialite) || empty($this->categorie));
	}
}