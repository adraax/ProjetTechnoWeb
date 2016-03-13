<?php
namespace GJLMFramework;

abstract class BaseController extends ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $module = '';
    protected $action = '';
    protected $page = null;
    protected $view = '';
    protected $managers = null;

    /* *********** Constructeur ********** */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);
        $this->page = new Page($app);

        $this->setModule($module);
        $this->setAction($action);
        $this->setView($action);
    }

    /* ********** Getter ********** */
    public function getPage()
    {
        return $this->page;
    }

    /* ********** Setter ********** */
    public function setModule($module)
    {
        if(!is_string($module) || empty($module))
        {
            throw new \InvalidArgumentException("Le module doit être une chaine de caractères non vide.");
        }

        $this->module = $module;
    }

    public function setAction($action)
    {
        if(!is_string($action) || empty($action))
        {
            throw new \InvalidArgumentException("L'action doit être une chaine de caractère non vide.");
        }

        $this->action = $action;
    }

    public function setView($view)
    {
        if(!is_string($view) || empty($view))
        {
            throw new \InvalidArgumentException("La vue doit être une chaine de caractère non vide.");
        }

        $this->view = $view;
        $this->page->setContentFile(__DIR__.'/../../App/'.$this->app->getName().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
    }

    /* ********** Méthode ********** */
    public function execute()
    {
        $method = $this->action.'Action';

        if(!is_callable([$this, $method]))
        {
            throw new \RuntimeException("L'action ".$this->action." n'est pas définie sur ce module.");
        }

        $this->$method($this->app->getHttpRequest());
    }
}
