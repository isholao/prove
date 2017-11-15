<?php

namespace Prove\Rules;

class Between extends \Prove\AbstractRule
{

    protected $min;
    protected $max;
    protected $include = TRUE;

    function __construct()
    {
        $this->name = 'between';
    }

    public function __invoke(float $min, float $max, bool $include = TRUE,
                             ?string $message = NULL)
    {
        if ($min > $max)
        {
            throw new \Error('Minimum value must be less than the maximum value.');
        }

        $this->message = $message ?? '%s must be between ' . $min . ' and ' . $max . (!$include ? ' (Without limits).' : '.');
        $this->min = $min;
        $this->max = $max;
        $this->include = $include;
    }

    public function validate(&$val): bool
    {
        if (\mb_strlen((string)$val) === 0)
        {
            return TRUE;
        }
        
        $tempVal = (float) $val;

        //check min
        if (!(($tempVal > $this->min) || ($this->include && $tempVal === $this->min)))
        {
            return FALSE;
        }

        //check max
        if (!(($tempVal < $this->max) || ($this->include && $tempVal === $this->max)))
        {
            return FALSE;
        }

        return TRUE;
    }

}
