<?php

namespace Prove;

/**
 * 
 * @method Prover alpha(?string  $message = NULL)
 * @method Prover between(float $min, float $max, bool $include = true,?string $message = NULL)
 * @method Prover boolean(?string  $message = NULL)
 * @method Prover date(string $format, ?string $message = NULL)
 * @method Prover digit(?string  $message = NULL)
 * @method Prover email(?string  $message = NULL)
 * @method Prover endswith(string $string, bool $preserveWhitespace = true,bool $caseInsensitive = false,?string  $message = NULL)
 * @method Prover equals(string $value, bool $caseInsensitive = true,?string  $message = NULL)
 * @method Prover ip(?string  $message = NULL)
 * @method Prover length(float $limit,?string  $message = NULL)
 * @method Prover matches(string $field, string $label, ?string  $message = NULL)
 * @method Prover max(float $limit, ?string  $message = NULL)
 * @method Prover min(float $limit, ?string  $message = NULL)
 * @method Prover notmatches(string $field, string $label, ?string  $message = NULL)
 * @method Prover oneof(string|array $allowed, string $delimiter, ?string  $message = NULL)
 * @method Prover regex(string $regex, ?string  $message = NULL)
 * @method Prover required(?string  $message = NULL)
 * @method Prover startswith(string $string, bool $caseInsensitive = false,?string  $message = NULL)
 * @method Prover uri(?string  $message = NULL)
 * 
 * @author Ishola O <ishola.tolu@outlook.com>
 */
class Prover
{

    /**
     *
     * @var $fields 
     */
    private $labels = [];

    /**
     *
     * @var $data 
     */
    private $data = [];

    /**
     *
     * @var array 
     */
    private $rules = [];

    /**
     *
     * @var array 
     */
    private $errors = [];

    /**
     *
     * @var array 
     */
    private $validData = [];

    /**
     *
     * @var array 
     */
    private $activeRules = [];

    /**
     *
     * @var array
     */
    protected static $defaultRules = [
        'required' => __NAMESPACE__ . '\\Rules\\Required',
        'alpha' => __NAMESPACE__ . '\\Rules\\Alpha',
        'between' => __NAMESPACE__ . '\\Rules\\Between',
        'boolean' => __NAMESPACE__ . '\\Rules\\Boolean',
        'date' => __NAMESPACE__ . '\\Rules\\Date',
        'digit' => __NAMESPACE__ . '\\Rules\\Digit',
        'email' => __NAMESPACE__ . '\\Rules\\Email',
        'endswith' => __NAMESPACE__ . '\\Rules\\EndsWith',
        'equals' => __NAMESPACE__ . '\\Rules\\Equals',
        'ip' => __NAMESPACE__ . '\\Rules\\Ip',
        'length' => __NAMESPACE__ . '\\Rules\\Length',
        'matches' => __NAMESPACE__ . '\\Rules\\Matches',
        'max' => __NAMESPACE__ . '\\Rules\\Max',
        'min' => __NAMESPACE__ . '\\Rules\\Min',
        'notmatches' => __NAMESPACE__ . '\\Rules\\NotMatches',
        'oneof' => __NAMESPACE__ . '\\Rules\\OneOf',
        'regex' => __NAMESPACE__ . '\\Rules\\Regex',
        'startswith' => __NAMESPACE__ . '\\Rules\\StartsWith',
        'uri' => __NAMESPACE__ . '\\Rules\\Uri',
    ];

    /**
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * Load a new rule
     * 
     * @param IRule $rule
     * @throws \Error
     */
    protected function loadRule(IRule $rule)
    {
        if (!\method_exists($rule, '__invoke'))
        {
            throw new \InvalidArgumentException('Invalid Rule. Rule must implement `__invoke` method.');
        }

        if (!\method_exists($rule, 'validate'))
        {
            throw new \InvalidArgumentException('Invalid Rule. Rule must implement `validate` method.');
        }

        $ruleName = \strtolower($rule->getName());
        if (!isset($this->rules[$ruleName]))
        {
            $rule->setProve($this);
            $this->rules[$ruleName] = $rule;
            $this->activeRules[$ruleName] = true;
        }
    }

    /**
     * Set data
     * 
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = \array_merge($this->data, $data);
    }

    /**
     * Get value
     *
     * @param string $key
     * @return type
     */
    public function getValue(string $key)
    {
        $array = $this->data;
        $keys = \explode('.', $key);
        // search the array using the dot character to access nested array values
        foreach ($keys as $key)
        {
            // when a key is not found or we didnt get an array to search return a fallback value
            if (!\is_array($array) || !\array_key_exists($key, $array))
            {
                return NULL;
            }

            $array = &$array[$key];
        }

        return $array;
    }

    /**
     * Validate data
     * 
     * @param string $key
     * @param string|NULL $label
     * @param bool $recursive
     * @return mixed
     * @throws \Error
     */
    public function validate(string $key, ?string $label = NULL,
                             bool $recursive = FALSE)
    {
        if (empty($this->activeRules))
        {
            throw new \Error('No active rules');
        }

        $this->labels[$key] = (empty($label)) ? \ucwords(\str_replace(['_', '-',
                    '.'], ' ', $key)) : $label;

        $val = $this->getValue($key);
        // validate the piece of data
        if (\is_array($val))
        {
            if (!$recursive)
            {
                throw new \Error('`' . $this->labels[$key] . '` cannot be recursively validated.');
            }

            return $this->process($key, $val, TRUE);
        }

        return $this->process($key, $val, FALSE);
    }

    /**
     * Add Rule
     * 
     * @param \Prove\AbstractRule $rule
     * @return $this
     */
    public function addRule(AbstractRule $rule)
    {
        $this->loadRule($rule);
        return $this;
    }

    /**
     * Process data
     * 
     * @param string $key
     * @param mixed $val
     * @param bool $recursive
     * @return bool
     */
    protected function process(string &$key, &$val, bool $recursive = FALSE): bool
    {
        if ($recursive && \is_array($val))
        {
            // run validations on each element of the array
            foreach ($val as &$valValue)
            {
                if (!$this->process($key, $valValue, $recursive))
                {
                    // halt validation for this value.
                    return FALSE;
                }
            }

            return FALSE;
        }

// try each rule function
        foreach ($this->activeRules as $name => $true)
        {
            $rule = $this->rules[$name];
            $rule->setProve($this);
            if ($rule->validate($val) === FALSE)
            {
                //register error
                $this->errors[$key][$name] = \sprintf($rule->message,
                                                      $this->labels[$key]);
                $this->activeRules = [];  // reset rules
                return FALSE;
            }
        }
        $this->activeRules = []; // reset rules
        $this->validData[$key] = $val;
        return TRUE;
    }

    /**
     * Get field label
     * 
     * @param string $field
     * @return string
     */
    public function getLabel(string $field): string
    {
        if (\array_key_exists($field, $this->labels))
        {
            return $this->labels[$field];
        }
        return $field;
    }

    /**
     * Get valid data
     * 
     * @return array
     */
    public function getValidatedData(): array
    {
        return $this->validData;
    }

    /**
     * Whether errors have been found.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get specific error.
     *
     * @param string $field
     *
     * @return string|NULL
     */
    public function getError(string $field, ?string $default = NULL)
    {
        return $this->errors[\strtolower($field)] ?? $default;
    }

    /**
     * Get all errors.
     *
     * @param bool $keys
     * @return array
     */
    public function getAllErrors(bool $keys = TRUE): array
    {
        return $keys ? $this->errors : \array_values($this->errors);
    }

    public function __call(string $name, array $arguments = [])
    {
        if (!\method_exists($this, $name))
        {
            $func = \strtolower($name);
            if (!isset($this->rules[$func]))
            {
                if (isset(self::$defaultRules[$func]))
                {
                    $this->loadRule(new self::$defaultRules[$func]);
                } else
                {
                    throw new \InvalidArgumentException('"' . $name . '" is an undefined method.');
                }
            }

            \call_user_func_array($this->rules[$func], $arguments);
            $this->activeRules[$func] = TRUE;
        }

        return $this;
    }

}
