<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * Confirm Rule
 * valid the field equal another field
 * @author suntianxiang <334965556@qq.com>
 */
class Confirm implements Rule
{
    private $confimedField;

    public function __construct($confimedField)
    {
        $this->confimedField = $confimedField;    
    }
    public function getName()
    {
        return 'confirm';
    }

    public function pass($value, Validator $context)
    {
        return $value != $context->getValue($this->confimedField);
    }

    public function getMessage()
    {
        return '%s field must equal to field '.$this->confirmField;
    }
}