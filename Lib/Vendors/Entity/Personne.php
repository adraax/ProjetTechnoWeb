<?php
namespace \Entity;

use \GJLMFramework\Entity;

class Personne extends Entity
{
    protected $prenom,
                $nom,
                $adresse,
                $date_naissance,
                $email,
                $certif_med, 
                $linked;
                
    const PRENOM_INV = 1;
    const NOM_INV = 2;
    const ADRESSE_INV = 3;
    const DATE_NAISSANCE_INV = 4;
    const EMAIL_IN = 5;
    const CERTIF_MED_INV = 6;
    const LINKED_INV = 7;
    
                
    /* ********** Setter ********** */
    public function setPrenom($prenom)
    {
        if(!is_string($prenom) || empty($prenom))
        {
            $this->errors[] = self::PRENOM_INV;
        }
        
        $this->prenom = $prenom;
    }
    
    public function setNom($nom)
    {
        if(!is_string($nom) || empty($nom))
        {
            $this->errors[] = self::NOM_INV;
        }
        
        $this->nom = $nom;
    }
    
    public function setAdresse($adresse)
    {
        if(!is_string($adresse) || empty($adresse))
        {
            $this->errors[] = self::ADRESSE_INV;
        }
        
        $this->adresse = $adresse;
    }
    
    public function setDate_naissance($date)
    {
        if(!is_string($date) || empty($date))
        {
            $this->errors[] = self::DATE_NAISSANCE_INV;
        }
        
        $this->date_naissance = $date;
    }
    
    public function setEmail($email)
    {
        if(!is_string($email) || empty($email))
        {
            $this->errors[] = self::EMAIL_INV;
        }
        
        $this->email = $email;
    }
    
    public function setCertif_med($certif)
    {
        if($certif !== 0 || $certif !== 1)
        {
            $this->errors[] = self::CERTIF_MED_INV;
        }
        
        $this->certif = $certif;
    }
    
    public function setLinked($linked)
    {
        if($linked !== 0 || $linked !== 1)
        {
            $this->errors[] = self::LINKED_INV;
        }
        
        $this->linked = $linked;
    }
    
    /* ********** Getter ********** */
    public function getPrenom()
    {
        return $this->prenom;
    }
    
    public function getNom()
    {
        return $this->nom;
    }
    
    public function getAdresse()
    {
        return $this->adresse;
    }
    
    public function getDate_naissance()
    {
        return $this->date_naissance;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getCertif_med()
    {
        return $this->certif_med;
    }
    
    public function getLinked()
    {
        return $this->linked;
    }
}
