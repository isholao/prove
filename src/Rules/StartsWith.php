<?php

namespace Prove\Rules;

class StartsWith extends \Prove\AbstractRule
{

    protected $caseInsensitive;

    function __invoke(string $string, bool $caseInsensitive = FALSE,
                      ?string $message = NULL)
    {
        $this->message = $message ?? '`%s` must start with `' . $string . '`.';
        $this->string = $caseInsensitive ? \strtolower($string) : $string;
        $this->caseInsensitive = $caseInsensitive;
    }

    function __construct()
    {
        $this->name = 'startswith';
    }

    public function validate(&$val): bool
    {
        return (\strlen((string) $val) !== 0 && (\substr($this->caseInsensitive ? \strtolower((string) $val) : (string) $val,
                                                                                              0,
                                                                                              \strlen($this->string)) === $this->string));
    }

}
