<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Email extends AbstractRule
{

    function __construct()
    {
        $this->name = 'email';
    }

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? 'Invalid "%s" email address.';
    }

    public function validate(&$val): bool
    {
        return !(\filter_var($val, FILTER_VALIDATE_EMAIL) === FALSE);
    }

}
