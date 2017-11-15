
[![Build Status](https://travis-ci.org/isholao/prove.svg?branch=master)](https://travis-ci.org/isholao/prove)

Install
-------

To install with composer:

```sh
composer require isholao/prove
```

Requires PHP 7.1 or newer.

Usage
-----

Here's a basic usage example:

```php
<?php

require '/path/to/vendor/autoload.php';

$data = [
    'name' => 'Ishola O',
    'age' =>'100'
];

$prover = new \Prove\Prover($data);
$prover->required()->regex('[a-zA-Z ]+')->validate('name');

if($prover->hasErrors()){
    echo $prover->getAllErrors() or $prover->getError('name');
}

```

Adding new rule

```php
<?php

class CustomRule extends \Prove\AbstractRule 
{
    function __construct()
    {
        $this->name = 'customrule';
    }

    public function __invoke(?string $message = NULL)
    {
        $this->message = $message ?? 'Custom error goes here';
    }

    public function validate(&$val): bool
    {
        return true or false
    }
}

$data = [
    'name' => 'Ishola O',
    'age' =>'100'
];

$prover = new \Prove\Prover($data);
$prover->addRule(new CustomRule());
$prover->addRule(new class extends \Prove\AbstractRule
        {

            public function __construct()
            {
                $this->name = 'custom';
            }

            public function __invoke(?string $message = NULL)
            {
                $this->message = $message ?? 'Custom error goes here.';
            }

            public function validate(&$val): bool
            {
                return false;
            }
        });
$prover->customrule('optional error message goes here or use default')->validate('name','Label goes here');

```
