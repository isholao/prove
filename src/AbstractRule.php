<?php

namespace Prove;

/**
 *
 * @author Ishola O <ishola.tolu@outlook.com>
 */
abstract class AbstractRule implements IRule
{

    protected $name;
    public $message;
    protected $prover;

    /**
     * Set rule engine
     * @param \Prove\Prover $prover
     */
    public function setProve(\Prove\Prover &$prover)
    {
        $this->prover = $prover;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
