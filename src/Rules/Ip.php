<?php

namespace Prove\Rules;

class Ip extends \Prove\AbstractRule
{

    function __construct()
    {
        $this->name = 'ip';
    }

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? 'IP address "%s" is invalid.';
    }

    public function validate(&$val): bool
    {
        return \filter_var($val, \FILTER_VALIDATE_IP,
                           FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE;
    }

}
