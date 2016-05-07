<?php
namespace Entity;

use \GJLMFramework\Entity;

class Competition extends Entity
{
    /* ********** Propriétés ********** */
    protected $niveau,
                $adresse, //L'adresse est séparée en 3 morceaux pour facilité les recherches (itinéraire...)
                $code_postal,
				$ville, 
				$lien_map, //Lien vers une carte avec l'adresse de la compétition (https://www.google.fr/maps/search/ville)
				$lien_itineraire, //Lien vers une carte avec l'itinéraire Dijon-compétition (https://www.google.fr/maps/dir/Dijon/ville)
				$meteo,
				$date, //Date de la compétition
				$type_hebergement,
				$mode_transport,
				$club_organisateur;
                
    const NIVEAU_INV = 1;
    const ADRESSE_INV = 2;
    const CODE_POSTAL_INV = 3;
	const VILLE_INV = 4;
	const LIEN_MAP_INV = 5;
	const LIEN_ITINERAIRE_INV = 6;
	const METEO_INV = 7;
	const DATE_INV = 8;
	const TYPE_HEBERGEMENT_INV = 9;
	const MODE_TRANSPORT_INV = 10;
	const CLUB_ORGANISATEUR_INV = 11;
    
    /* *********** Getter ********** */
    public function getNiveau()
    {
        return $this->niveau;
    }
    
    public function getAdresse()
    {
        return $this->adresse;
    }
    
    public function getCode_postal()
    {
        return $this->code_postal;
    }
	
	public function getVille()
    {
        return $this->ville;
    }
	
	public function getLien_map()
	{
		return $this->lien_map;
	}
	
	public function getLien_itineraire()
	{
		return $this->lien_itineraire;
	}
	
	public function getMeteo()
	{
		return $this->meteo;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function getType_hebergement()
	{
		return $this->type_hebergement;
	}
	
	public function getMode_transport()
	{
		return $this->mode_transport;
	}
	
	public function getClub_organisateur()
	{
		return $this->lien_itineraire;
	}
    
    /* ********** Setter ********** */
    public function setNiveau($niveau)
    {
        if (!is_string($niveau) || empty($niveau))
        {
            $this->errors[] = self::NIVEAU_INV;
        }
        
        $this->niveau = $niveau;
    }
    
    public function setAdresse($adresse)
    {
        if(!is_string($adresse) || empty($adresse))
        {
            $this->errors[] = self::ADRESSE_INV;
        }
        
        $this->adresse = $adresse;
    }
    
    public function setCode_postal($code_postal)
    {
        if(!is_int($code_postal) || empty($code_postal))
        {
            $this->errors[] = self::CODE_POSTAL_INV;
        }
        
        $this->code_postal = $code_postal;
    }
	
	public function setVille($ville)
    {
        if(!is_string($ville) || empty($ville))
        {
            $this->errors[] = self::VILLE_INV;
        }
        
        $this->ville = $ville;
    }
	
	public function setLien_map($lien_map)
    {
        if(!is_string($lien_map) || empty($lien_map))
        {
            $this->errors[] = self::LIEN_MAP_INV;
        }
        
        $this->lien_map = $lien_map;
    }
	
	public function setLien_itineraire($lien_itineraire)
    {
        if(!is_string($lien_itineraire) || empty($lien_itineraire))
        {
            $this->errors[] = self::LIEN_ITINERAIRE_INV;
        }
        
        $this->lien_itineraire = $lien_itineraire;
    }
	
	public function setMeteo($meteo)
    {
        if(!is_string($meteo) || empty($meteo))
        {
            $this->errors[] = self::METEO_INV;
        }
        
        $this->meteo = $meteo;
    }
	
	public function setDate($date)
    {
		$d = DateTime::createFromFormat('d/m/Y', $date);
        if($d->format($date) != $date || empty($date))
        {
            $this->errors[] = self::DATE_INV;
        }
        
        $this->date = $d;
    }
	
	public function setType_hebergement($type_hebergement)
    {
        if(!is_string($type_hebergement) || empty($type_hebergement))
        {
            $this->errors[] = self::TYPE_HEBERGEMENT_INV;
        }
        
        $this->type_hebergement = $type_hebergement;
    }
	
	public function setMode_transport($mode_transport)
    {
        if(!is_string($mode_transport) || empty($mode_transport))
        {
            $this->errors[] = self::MODE_TRANSPORT_INV;
        }
        
        $this->mode_transport = $mode_transport;
    }
	
	public function setClub_organisateur($club_organisateur)
    {
        if(!is_string($club_organisateur) || empty($club_organisateur))
        {
            $this->errors[] = self::CLUB_ORGANISATEUR_INV;
        }
        
        $this->club_organisateur = $club_organisateur;
    }
}