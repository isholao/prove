<?php

namespace Prove\Rules;

class NotMatches extends \Prove\AbstractRule
{

    protected $field;
    protected $label;

    public function __invoke(string $field, string $label,
                             ?string $message = NULL)
    {
        $this->message = $message ?? '`%s` must not match `' . $field . '`.';
        $this->field = $this->prover->getValue($field);
        $this->label = $label;
    }

    public function validate(&$val): bool
    {
        return ($this->field !== (string) $val);
    }

    function __construct()
    {
        $this->name = 'notmatches';
    }

}
