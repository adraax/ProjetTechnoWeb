<?php
namespace GJLMFramework;

abstract class Manager
{
    protected $dao; //Database Access Object

    public function __construct($dao)
    {
        $this->dao = $dao;
    }
}
