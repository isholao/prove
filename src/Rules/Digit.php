<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Digit extends AbstractRule
{

    function __construct()
    {
        $this->name = 'digit';
    }

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? '%s must consist only digits.';
    }

    public function validate(&$val): bool
    {
        return \is_numeric($val);
    }

}
