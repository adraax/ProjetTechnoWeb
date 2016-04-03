<?php
namespace GJLMFramework;

class NotNullValidator extends Validator
{
    public function isValid($value)
    {
        return $value != '';
    }
}