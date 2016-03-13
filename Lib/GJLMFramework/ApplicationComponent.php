<?php
namespace GJLMFramework;

/**
* Classe qui représente un composant de l'Application
* Par exemple : BaseController, Router, Config ...
*/
abstract class ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $app; //référence de notre application

    /* *********** Constructeur ********** */
    public function __construct(Application $appli) //crée le composant de l'application en lui donnant la référence de son application
    {
        $this->app = $appli;
    }

    /* *********** Getter ********** */
    public function getApp()
    {
        return $this->app;
    }
}
