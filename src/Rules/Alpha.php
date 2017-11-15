<?php

namespace Prove\Rules;

class Alpha extends \Prove\AbstractRule
{

    function __construct()
    {
        $this->name = 'alpha';
    }

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? 'Invalid `%s` alphabets.';
    }

    public function validate(&$val): bool
    {
        return \preg_match('#^[a-zA-Z ]+$#i', (string)$val) === 1;
    }

}
