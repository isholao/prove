<?php

namespace Prove\Rules;

class Equals extends \Prove\AbstractRule
{

    protected $value;
    protected $caseInsensitive;

    function __construct()
    {
        $this->name = 'equals';
    }

    public function __invoke(string $value, bool $caseInsensitive = TRUE,
                             ?string $message = NULL)
    {
        $this->message = $message ?? '%s must be equal to "' . $value . '".';
        $this->value = $value;
        $this->caseInsensitive = $caseInsensitive;
    }

    public function validate(&$val): bool
    {
        return ($this->value === ($this->caseInsensitive ? \strtolower((string)$val) : (string)$val));
    }

}
