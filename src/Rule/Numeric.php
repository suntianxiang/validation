<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * the numeric rule
 * check field is a numeric
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class numeric implements Rule
{
    public function getName()
    {
        return 'numeric';
    }

    public function pass($value, Validator $context)
    {
        return is_numeric($value);
    }

    public function getMessage()
    {
        return '%s is not a numeric';
    }
}
