<?php

namespace Validation\Rule;

use Validation\Validator;

/**
 * the condition rule
 * if the callable $this->when return true pass by data[$this->field] value
 * the $this->then rule will be validate. otherwise, the $this->then rule will not validate
 * @author suntianxiang <334965556@qq.com>
 */
class WhenThen implements Rule
{
    /** @var string error message */
    private $message;

    /** @var string the field pass be $this->when */
    private $field;

    /** @var callable the condition callback */
    private $when;

    /** @var array|string|Rule then rules */
    private $then;

    public function __construct($field, callable $when = null, $then = null)
    {
        $this->field = $field;
        $this->when = $when;
        $this->then = $then;
    }

    /**
     * set the condition
     * 
     * @param string $field
     * @param callable $when
     * @return $this
     */
    public function when($field, callable $when)
    {
        $this->field = $field;
        $this->when = $when;
        return $this;
    }

    /**
     * set rule
     *
     * @param string|array|Rule $r
     * @return $this
     */
    public function then($r)
    {
        $this->then = $r;
        return $this;
    }

    public function getName()
    {
        return 'whenThen';
    }

    public function pass($value, Validator $context)
    {
        if (call_user_func($this->when, $context->getValue($this->when['field']))) {
            $validator = new Validator([
                'tmp' => $value
            ]);
            $validator->setRule('tmp', $this->then);
            $result = $validator->validate();
            if(!$result) {
                $errors = $validator->getErrors()['tmp'];
                $this->message = implode(';', $errors);
            }

            return $result;
        }

        return true;
    }

    public function getMessage()
    {
        return $this->message;
    }
}