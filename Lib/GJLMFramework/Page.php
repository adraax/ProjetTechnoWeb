<?php
namespace GJLMFramework;

/**
 * Classe qui génère la page à envoyer au navigateur
 */
class Page extends ApplicationComponent
{
    /* ********** Propriétés ********** */
    protected $contentFile; //fichier contenant la vue à utiliser (Modèle MVC)
    protected $vars = []; //variables à envoyer à la vue

    /* ********** Getter ********** */
    public function getGeneratedPage()
    {
        if(!file_exists($this->contentFile))
        {
            throw new RuntimeException("La vue spécifiée n'existe pas : ".$this->contentFile);
        }

        $uri = $this->app->getHttpRequest()->getRequestURI();
        $nb = mb_substr_count($uri, '/');
        $path = "";
        for ($i=0; $i < $nb-1; $i++)
        {
            $path .= "../";
        }
        $user = $this->app->getUser();

        extract($this->vars);

        ob_start();
            require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
            require __DIR__.'/../../App/'.$this->app->getName().'/Templates/layout.php';
        return ob_get_clean();
    }

    /* ********** Setter ********** */
    public function setContentFile($contentFile)
    {
        if(!is_string($contentFile) || empty($contentFile) || !file_exists($contentFile))
        {
            throw new \InvalidArgumentException("La vue spécifiée est invalide : ".$contentFile);
        }

        $this->contentFile = $contentFile;
    }

    /* ********** Méthodes ********** */
    public function addVar($var, $value)
    {
        if(!is_string($var) || is_numeric($var) || empty($var))
        {
            throw new \InvalidArgumentException("Le nom de la variable doit être une chaine de caractère non nulle");
        }

        $this->vars[$var] = $value;
    }
}
