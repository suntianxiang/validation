<?php


namespace Validation\Rule;

use Validation\Validator;

/**
 * Callable Rule
 * if rule pass, the callback function return boolean. otherwise, return false
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class CallableRule implements Rule
{
    /**
     * rule name
     *
     * @var string
     */
    protected $name;

    /**
     * callback function
     *
     * @var Closure
     */
    protected $callable;

    /**
     * error message
     *
     * @var string
     */
    protected $error;

    /**
     * construct method
     *
     * @param string $name
     * @param Closure $callable
     * @return $this
     */
    public function __construct($name, $callable)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->callable->bindTo($this);
    }

    public function getName()
    {
        return $this->name;
    }

    public function pass($value, Validator $context)
    {
        $func = $this->callable;

        return $func($value);
    }

    public function getMessage()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}
