<?php
namespace GJLMFramework;

class Form
{
    /* ********** Propriétés ********** */
    protected $entity;
    protected $fields = [];
    
    /* ********** Constructeur ********** */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }
    
    /* ********** Getter ********** */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /* ********** Setter ********** */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }
    
    /* ********** Méthodes ********* */
    public function add(Field $field)
    {
        $attr = 'get'.ucfirst($field->getName()); //on récupère le nom du champ
        $field->setValue($this->entity->$attr()); //on assigne la valeur de l'attribut au champ
        
        $this->fields[] = $field; //on ajoute le champ à la liste des champs
        return $this; //on retourne le formulaire pour pouvoir ajouter des champs plus facilement
    }
    
    public function isValid()
    {
        $valid = true;
        
        //on vérifie que tout les champs sont valides
        foreach ($this->fields as $field ) 
        {
            if(!$field->isValid())
            {
                $valid = false;
            }
        }
        
        return $valid;
    }
    
    public function createView()
    {
        $view = '';
        
        //on génère les champs du formulaire
        foreach ($this->fields as $field) 
        {
            $view .= $field->buildWidget();
        }
        
        return $view;
    }
}