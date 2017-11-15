<?php

declare(strict_types=1);

namespace Prove\Rules;

class EndsWith extends \Prove\AbstractRule
{

    protected $string;
    protected $caseInsensitive;

    function __construct()
    {
        $this->name = 'endswith';
    }

    function __invoke(string $string, bool $preserveWhitespace = TRUE,
                      bool $caseInsensitive = FALSE, ?string $message = NULL)
    {
        $this->message = $message ?? '%s must end with "' . $string . '".';
        $data = $caseInsensitive ? \strtolower($string) : $string;
        $this->string = $preserveWhitespace ? $data : \trim($data);
        $this->caseInsensitive = $caseInsensitive;
    }

    public function validate(&$val): bool
    {
        $val = (string) $val;
        return ((\mb_strlen($val) === 0) || (\substr($this->caseInsensitive ? \strtolower($val) : $val,
                                                                                       -\mb_strlen($this->string)) === $this->string));
    }

}
