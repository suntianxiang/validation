<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * Rule interface
 */
interface Rule
{
    /**
     * get rule name
     *
     * @return string
     */
    public function getName();

    /**
     * execute the rule
     *
     * @param mixed
     * @param \Validation\Validator
     * @return boolean
     */
    public function pass($value, Validator $context);

    /**
     * get error message
     *
     * @return string
     */
    public function getMessage();
}
