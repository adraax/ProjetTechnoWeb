<?php
namespace GJLMFramework;

/**
 * Classe représentant une route, c'est à dire un URL à laquelle un controller est associé
 */
class Route
{
    /* ********** Propriétés ********** */
    protected $module; //Module de l'application
    protected $action; //Action du module a éxécuter
    protected $url; //URL de la route
    protected $paramNames = []; //noms des paramètres de la route
    protected $params = []; //valeur des paramètres de la route

    /* *********** Constructeur ********** */
    public function __construct($url, $module, $action, array $paramNames)
    {
        $this->setModule($module);
        $this->setAction($action);
        $this->setUrl($url);
        $this->setParamNames($paramNames);
    }

    /* ********** Getter ********** */
    public function getModule()
    {
        return $this->module;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParamNames()
    {
        return $this->paramNames;
    }

    public function getParams()
    {
        return $this->params;
    }

    /* ********** Setter ********** */
    public function setModule($module)
    {
        if(!is_string($module))
        {
            throw new \InvalidArgumentException("Le module doit être une chaine de caractère : ".$module);
        }
        else
        {
            $this->module = $module;
        }
    }

    public function setAction($action)
    {
        if(!is_string($action))
        {
            throw new \InvalidArgumentException("L'action doit être une chaine de caractère : ".$action);
        }
        else
        {
            $this->action = $action;
        }
    }

    public function setUrl($url)
    {
        if(!is_string($url))
        {
            throw new \InvalidArgumentException("L'URL doit être une chaine de caractère : ".$url);
        }
        else
        {
            $this->url = $url;
        }
    }

    public function setParamNames(array $paramNames)
    {
        $this->paramNames = $paramNames;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /* ********** Méthodes ********** */
    public function hasParams()
    {
        return !empty($this->paramNames);
    }

    //vérifie si l'url passée en paramètre correspond à la route.
    //Si oui on renvoie les éventuels paramètres de la route, sinon on renvoie faux
    public function match($url)
    {
        if(preg_match('`^'.$this->url.'$`', $url, $matches))
        {
            return $matches;
        }
        else
        {
            return false;
        }
    }
}
