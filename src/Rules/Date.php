<?php

namespace Prove\Rules;

use Prove\AbstractRule;

class Date extends AbstractRule
{

    protected $format = 'd/m/Y';

    function __construct()
    {
        $this->name = 'date';
    }

    public function __invoke(string $format, ?string $message = NULL)
    {
        if (empty($format))
        {
            $format = 'd/m/Y';
        }

        $this->message = $message ?? "%s is not valid date. Required format is '" . $format . "'.";
        $this->format = $format;
    }

    public function validate(&$val): bool
    {
        if (empty($val))
        {
            return FALSE;
        }

        try
        {
            $dt = new \DateTime((string) $val);
            return $dt->format($this->format) === (string)$val;
        } catch (\Throwable $e)
        {
            $e = NULL;
            return FALSE;
        }
    }

}
