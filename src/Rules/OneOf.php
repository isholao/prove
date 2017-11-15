<?php

namespace Prove\Rules;

class OneOf extends \Prove\AbstractRule
{

    protected $allowed;
    protected $delimiter = ',';

    public function __invoke($allowed, string $delimiter = ',',
                             ?string $message = NULL)
    {

        $token = empty($delimiter) || \is_NULL($delimiter) ? ' ' : $delimiter;
        $options = [];
        if (\is_string($allowed))
        {
            $options = \explode($token, $allowed);
        } elseif (\is_array($allowed))
        {
            $options = $allowed;
        }

        $this->message = $message ?? '`%s` must be one of [`' . \implode('`, `',
                                                                         $options) . '`].';
        $this->allowed = $options;
        $this->delimiter = $delimiter;
    }

    public function validate(&$val): bool
    {
        return \in_array((string)$val, $this->allowed);
    }

    function __construct()
    {
        $this->name = 'oneof';
    }

}
