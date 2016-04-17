<?php
namespace GJLMFramework;

session_start();

class User
{
    /* *********** Getter ********** */
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    /* ********** Setter *********** */
    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setFlash($value, $type)
    {
        $_SESSION['flash'] = $value;
        $_SESSION['flash_type'] = $type;
    }

    public function setAuthenticated($authenticated = true)
    {
        if(!is_bool($authenticated))
        {
            throw new InvalidArgumentException("La valeur spécifiée à la méthode User::setAuthenticated() doit être un booleen");
        }

        $_SESSION['auth'] = $authenticated;
    }

    /* *********** Méthodes *********** */
    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }
}
