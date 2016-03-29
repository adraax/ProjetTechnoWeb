<?php
namespace GJLMFramework;

abstract class FormBuilder
{
    /* ********** Proopriétés *********** */
    protected $form;
    
    /* ********** Constructeur ********** */
    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }
    
    /* ********** Getter *********** */
    public function getForm()
    {
        return $this->form;
    }
    
    /* ********** Setter *********** */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }
    
    /* *********** Méthodes ********** */
    abstract public function build();
}