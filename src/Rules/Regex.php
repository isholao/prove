<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Regex extends AbstractRule
{

    protected $regex;

    public function __invoke(string $regex, ?string $message = NULL)
    {
        $this->message = $message ?? '`%s` does not match regex [`' . $regex . '`].';
        $this->regex = $regex;
    }

    public function validate(&$val): bool
    {
        if (\preg_match('#^' . $this->regex . '$#', NULL) !== FALSE)
        {
            return \preg_match('#^' . $this->regex . '$#i', $val) === 1;
        }

        return FALSE;
    }

    function __construct()
    {
        $this->name = 'regex';
    }

}
