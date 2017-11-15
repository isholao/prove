<?php

namespace Prove\Rules;

class Matches extends \Prove\AbstractRule
{

    protected $value;
    protected $label;

    function __construct()
    {
        $this->name = 'matches';
    }

    public function __invoke(string $field, string $label,
                             ?string $message = NULL)
    {
        $this->message = $message ?? '`%s` value must match `' . $this->prover->getLabel($field) . '` value.';
        $this->value = $this->prover->getValue($field);
        $this->label = $label;
    }

    public function validate(&$val): bool
    {
        return ($this->value === (string)$val) !== FALSE;
    }

}
