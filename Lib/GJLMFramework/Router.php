<?php
namespace GJLMFramework;

/**
 * Classe représentant le Router
 * Gère le renvoi de routes correspondante à une URL
 */
class Router
{
    /* *********** Propriétés ********** */
    protected $routes = [];

    /* ********** Constantes ********** */
    const ROUTE_NOT_FOUND = 1;

    /* *********** Getter ********** */
    public function getRoute($url)
    {
        foreach ($this->routes as $route)
        {
            //Si la route correspond a l'url
            if(($paramsValue = $route->match($url)) !== false)
            {
                //Si elle a des paramètres
                if($route->hasParams())
                {
                    $paramNames = $route->getParamNames();
                    $listParams = [];

                    //on crée le tableau associatif contenant les paramètres de la route
                    foreach ($paramsValue as $key => $value)
                    {
                        //La première clé contient toute la route analysée par preg_match()
                        //on ne traite donc pas cette clé
                        if($key !== 0)
                        {
                            $listParams[$paramNames[$key - 1]] = $value;
                        }
                    }

                    //on assigne le tableau de paramètres à la route
                    $route->setParams($listParams);
                }

                //on renvoie la route trouvée
                return $route;
            }
        }

        //Si l'url ne correspond à aucune route, on lance une Exception
        //L'application capturera l'Exception et retournera une erreur 404
        throw new \RuntimeException("Aucune route ne correspond à l'URL", self::ROUTE_NOT_FOUND);
    }

    /* *********** Méthode ********** */
    public function addRoute(Route $route)
    {
        if(!in_array($route, $this->routes))
        {
            $this->routes[] = $route;
        }
    }
}
