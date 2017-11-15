<?php

namespace Prove;

/**
 * @author Ishola O <ishola.tolu@outlook.com>
 */
interface IRule {

    
    /**
     * 
     * @param \Prove\Prover $prove
     */
    public function setProve(\Prove\Prover &$prove);

    /**
     * Validate rule
     * @return bool True/False
     */
    public function validate(&$val): bool;

    /**
     * Get rule name
     * @return string
     */
    public function getName(): string;
}
