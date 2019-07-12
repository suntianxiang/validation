<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * 最大长度
 *
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class Max implements Rule
{
    protected $max;

    /**
     * constructor
     *
     * @param array $params
     * @return $this
     */
    public function __construct($max)
    {
        $this->max = $max;
    }

    public function getName()
    {
        return 'max';
    }

    public function pass($value, Validator $context)
    {
        return mb_strlen($value, 'utf-8') <= $this->max;
    }

    public function getMessage()
    {
        return '%s 必须小于'.$this->max;
    }
}
