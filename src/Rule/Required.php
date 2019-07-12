<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * the required rule
 * check request must container field
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class Required implements Rule
{
    public function getName()
    {
        return 'required';
    }

    public function pass($value,Validator $context)
    {
        return null !== $value;
    }

    public function getMessage()
    {
        return '%s must be required';
    }
}
