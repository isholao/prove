<?php

namespace Prove\Rules;

class Uri extends \Prove\AbstractRule
{

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? '%s is an invalid url.';
    }

    public function validate(&$val): bool
    {
        return (\strlen(\trim($val)) === 0 || \filter_var($val,
                                                          \FILTER_VALIDATE_URL) !== FALSE);
    }

    function __construct()
    {
        $this->name = 'uri';
    }

}
