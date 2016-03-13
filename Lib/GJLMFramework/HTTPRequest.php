<?php
namespace GJLMFramework;

/**
 * Classe représentant une requête HTTP
 *
 * Permet d'accéder aux variables du serveur, aux cookies et aux données transmises en GET ou POST
 */
class HTTPRequest extends ApplicationComponent
{
    /* ********** Méthodes Exists ********** */
    public function cookieExists($var)
    {
        return isset($_COOKIE[$var]);
    }

    public function getExists($var)
    {
        return isset($_GET[$var]);
    }

    public function postExists($var)
    {
        return isset($_POST[$var]);
    }

    /* ********** Getter ********** */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD']; //renvoie GET ou POST
    }

    public function getRequestURI()
    {
        //Renvoie l'uri de la requete c'est à dire l'url sans le nom du site
        //par exmple pour http://www.test.loc/news/edit/3, l'uri est /news/edit/3
        return $_SERVER['REQUEST_URI'];
    }

    public function getCookieData($var)
    {
        //renvoie le contenu du cookie s'il existe, sinon renvoie null
        return isset($_COOKIE[$var]) ? $_COOKIE[$var] : null;
    }

    public function getGetData($var)
    {
        return isset($_GET[$var]) ? $_GET[$var] : null;
    }

    public function getPostData($var)
    {
        return isset($_POST[$var]) ? $_POST[$var] : null;
    }
}
