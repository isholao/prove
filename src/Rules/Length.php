<?php

namespace Prove\Rules;

class Length extends \Prove\AbstractRule
{

    protected $limit;

    function __construct()
    {
        $this->name = 'length';
    }

    public function __invoke(float $limit, ?string $message = NULL)
    {
        $this->message = $message ?? '%s must be at least more than `' . $limit . '` characters in length.';
        $this->limit = $limit;
    }

    public function validate(&$val): bool
    {
        return ((float) \mb_strlen((string)$val) > $this->limit) !== FALSE;
    }

}
