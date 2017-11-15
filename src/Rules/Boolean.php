<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Boolean extends AbstractRule
{

    function __construct()
    {
        $this->name = 'boolean';
    }

    public function __invoke(string $message = NULL)
    {
        $this->message = $message ?? '%s must be a bool value (1 or 0).';
    }

    public function validate(&$val): bool
    {
        return !(\filter_var($val, \FILTER_VALIDATE_BOOLEAN) === FALSE);
    }

}
