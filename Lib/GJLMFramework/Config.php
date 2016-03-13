<?php
namespace GJLMFramework;

/**
* Classe gérant la configuration de l'application grace aux paramètres contenu dans le fichier  ~/App/NomDeLApplication/Config/config.xml
*/
class Config extends ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $params = []; //Représente les paramètres du fichier de configuration

    /* *********** Getter ********** */
    public function get($param)
    {
        if(!$this->params)  //Si le tableau de paramètres n'existe pas, on lit le fichier de config
        {                   //Sinon on renvoie le paramètre s'il existe
            $xml = simplexml_load_file(__DIR__.'../../App/'.$this->app->getName().'/Config/config.xml'); //on charge le fichier

            $elements = $xml->define;

            foreach ($elements as $element)
            {
                //On rajoute chaque paramètre dans le tableau
                $this->params[(string)$element['param']] = (string)$element['value'];
            }
        }

        //renvoie du paramètre s'il existe
        if(isset($this->params[$param]))
        {
            return $this->params[$param];
        }

        //renvoie null si le paramètre n'existe pas
        return null;
    }
}
