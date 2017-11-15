<?php

namespace Prove\Tests;

use PHPUnit\Framework\TestCase;

class ProverTest extends TestCase
{

    public function testNoErrors()
    {
        $data = [
            'name' => 'Ishola O',
            'age' => '100'
        ];

        $prover = new \Prove\Prover($data);
        $prover->required()->regex('[a-zA-Z ]+')->validate('name');

        if ($prover->hasErrors())
        {
            echo $prover->getAllErrors() or $prover->getError('name');
        }

        $this->assertFalse($prover->hasErrors());
        $this->assertSame([], $prover->getAllErrors());
    }

    public function testCustomRuleWithExpectedError()
    {

        $data = [
            'age' => ['100']
        ];

        $prover = new \Prove\Prover($data);
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

        $prover->required()->custom()->validate('age.0');
        $this->assertTrue($prover->hasErrors());
        $this->assertSame(['age.0' => ['custom' => 'Custom error goes here.']],
                          $prover->getAllErrors());
    }

}
