<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * 在列表中规则
 *
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class In implements Rule
{
    /**
     * 可通过列表
     *
     * @var array
     */
    protected $in = [

    ];

    public function __construct(...$params)
    {
        $this->in = $params;
    }

    public function getName()
    {
        return 'in';
    }

    public function pass($value, Validator $context)
    {
        return in_array($value, $this->in);
    }

    public function getMessage()
    {
        return '%s must in ('.implode(',', $this->in).')';
    }
}
