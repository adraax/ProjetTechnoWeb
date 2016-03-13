<?php
$xml = simplexml_load_file('routes.xml');

foreach ($xml->route as $route)
{
    $params = [];

    //Si des paramètres sont présents dans l'url
    if(isset($route['params']))
    {
        $params = explode(',', (string)$route['params']);
    }

    //on ajoute la route au router
    echo (string)$route['url'].'<br/>';
    echo (string)$route['module'].'<br/>';
    echo (string)$route['action'].'<br/>';
}
