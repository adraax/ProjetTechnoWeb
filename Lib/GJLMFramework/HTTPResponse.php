<?php
namespace GJLMFramework;

/**
 * Classe représentant une réponse HTTP
 *
 * Permet d'envoyer la page à afficher au navigateur, de faire des redirections, de gérer les erreurs 404 ...
 */
class HTTPResponse extends ApplicationComponent
{
    /* *********** Propriétés ********** */
    protected $page; //page à envoyer à l'utilisateur

    /* ********** Setter ********** */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    public function setCookie($name, $value = "", $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /* ********** Méthodes ********** */
    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($location)
    {
        header('Location: '.$location);
        exit;  //arrete le script courant, et affiche le message passé en paramètre s'il existe
    }

    public function redirect404()
    {
        $this->page = new Page($this->getApp());
        $this->page->setContentFile(__DIR__.'/../../Errors/404.html');
        $this->page->addVar('title', "Erreur 404");

        $this->addHeader('HTTP/1.0 404 Not Found');

        $this->send();
    }

    public function send()
    {
        //arrete le traitement et affiche la page
        exit($this->page->getGeneratedPage());
    }
}
