<?php
namespace GJLMFramework;

class PDOFactory
{
    /*public static function getMysqlConnection($host, $name, $user, $password)
    {
        $db = new \PDO('"mysql:host'.$host.';dbname='.$name.'"', $user, $password);
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
    }*/
    
    public static function getMysqlConnection()
    {
        $db = new \PDO('mysql:host=localhost;dbname=projet_techno_web', 'root', '');
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $db;
     }
}
