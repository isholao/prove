<?php

namespace Prove\Rules;

class Min extends \Prove\AbstractRule
{

    protected $limit;

    public function __invoke(float $limit, ?string $message = NULL)
    {
        $this->message = $message ?? '%s must be greater than or equal to `' . $limit . '`.';
        $this->limit = $limit;
    }

    public function validate(&$val): bool
    {
        if (\floatval($val) == 0)
        {
            return TRUE;
        }
        return ($this->limit < (float) $val) !== FALSE;
    }

    function __construct()
    {
        $this->name = 'min';
    }

}
