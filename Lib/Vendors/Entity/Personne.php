<?php
namespace Entity;

use \GJLMFramework\Entity;

class Personne extends Entity
{
    protected $prenom,
                $nom,
                $adresse,
                $date_naissance,
                $email,
                $num_tel,
                $sexe;
                
    const PRENOM_INV = 1;
    const NOM_INV = 2;
    const ADRESSE_INV = 3;
    const DATE_NAISSANCE_INV = 4;
    const EMAIL_INV = 5;
    const TEL_INV = 6;
    const SEXE_INV = 7;
    
                
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
    
    public function setNum_tel($tel)
    {
        if(!is_int($tel))
        {
            $this->errors[] = self::TEL_INV;
        }
        
        $this->num_tel = $tel;
    }
    
    public function setSexe($sexe)
    {
        if($sexe !== "F" && $sexe !== "H")
        {
            $this->errors[] = self::SEXE_INV;
        }
        
        $this->sexe = $sexe;
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
    
    public function getNum_tel()
    {
        return $this->num_tel;
    }
    
    public function getSexe()
    {
        return $this->sexe;
    }
	
	//Affichage
	public function affichePersonne()
	{
		return '<p class="personne">Nom : '.$this->nom.'<br />'.
			'Prenom : '.$this->prenom.'<br />'.
			'Adresse : '.$this->adresse.'<br />'.
			'Date de naissance : '.date('d/m/Y', strtotime($this->date_naissance)).'<br />'.
			'Sexe : '.$this->sexe.'<br />'.
			'Email : '.$this->email.'<br />'.
			'Numéro de téléphone : '.$this->num_tel.'<br />'.
			'</p>';
	}
	
	public function isValid()
	{
		return !(empty($this->nom) || empty($this->prenom) || empty($this->adresse) || empty($this->date_naissance) || empty($this->email) || empty($this->num_tel) || empty($this->sexe));
	}
}
