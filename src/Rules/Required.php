<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Required extends AbstractRule
{

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? '%s is required.';
    }

    public function validate(&$val): bool
    {
        return \is_scalar($val);
    }

    function __construct()
    {
        $this->name = 'required';
    }

}
