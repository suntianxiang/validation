<?php

namespace Validation;
/**
 * Validator class
 *
 * @author suntianxiang <suntianxiang@sina.cn>
 * @version 1.0
 */
class Validator
{
    /**
     * field rules
     * structure: [field => rules], rules can be string or array
     * @var array
     */
    protected $rules;

    /**
     * the data
     *
     * @var string
     */
    protected $data;

    /**
     * error messages
     *
     * @var array
     */
    protected $errors = [];

    /**
     * custom messages
     *
     * @var array
     */
    protected $customMessages = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * set rule
     * rules can be:
     *      1. a Closure which return boolean.
     *      2. a instance implments Validation\Rule\Rule.
     *      3. a string with ruleclass::getName() mutiply rule seperate with "|", params seperate with ",".
     *usage:
     *      $validator->setRule("number", "max:5|required|in:1,2,3");
     * @param string $field
     * @param mixed $rules
     * @param string $messages custom messages ["rule" => "message"]
     * @return void
     */
    public function setRule($field, $rules, $messages = [])
    {
        $rules = $this->explodeRules($rules);
        $parse_rules = [];

        foreach ($rules as $rule) {
            $parse_rules[] = $this->prepareRule($rule);
        }

        if (!empty($messages)) {
            $this->customMessages[$field] = $messages;
        }

        $this->rules[$field] = $parse_rules;
    }

    /**
     * set rules
     *
     * @param array $rules field => rules
     * @param array $messages field => [ rule => message ]
     * @return void
     */
    public function setRules($rules, $messages = [])
    {
        foreach ($rules as $field => $value) {
            $this->setRule($field, $value);
        }

        $this->customMessages = $messages;

        return $this;
    }

    /**
     * set data
     *
     * @param array
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * get data value
     * 
     * @param string $field
     * @return mixed
     */
    public function getValue($field)
    {
        return isset($this->data[$field]) ? $this->data[$field] : null;
    }

    /**
     * execute validation
     *
     * @return boolean
     */
    public function validate()
    {
        if (!$this->data) {
            return false;
        }

        $pass = true;

        foreach ($this->rules as $key => $rules) {
            foreach ($rules as $rule) {
                $ruleObj = RuleFactory::make($rule);

                // if not has the field and it can be null, ignore it.
                if (!isset($this->data[$key]) && $this->isNullable($ruleObj)) {
                    continue;
                }

                $result = $ruleObj->pass(isset($this->data[$key]) ? $this->data[$key] : null, $this);

                if (!$result) {
                    $message = sprintf($ruleObj->getMessage(), $key);
                    $this->setError($key, $ruleObj->getName(), $message);
                    false === $pass || $pass = false;
                }
            }
        }

        return $pass;
    }

    /**
     * get error message
     *
     * @param string $field
     * @return string
     */
    public function getError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }

    /**
     * get all errors as a array
     *
     * @return array
     */
    public function getErrors()
    {
        $data = [];
        foreach ($this->errors as $field => $errors) {
            foreach ($errors as $ruleName => $error) {
                $data["$field.$ruleName"] = $error;
            }
        }

        return $data;
    }

    /**
     * get all errors as a stinrg
     *
     * @param
     * @return 
     */
    public function getErrorString()
    {
        return implode(';', $this->getErrors());
    }

    /**
     * set error message
     *
     * @param string $field
     * @param string $ruleName
     * @param string $message
     * @return void
     */
    public function setError($field, $ruleName, $message)
    {
        $this->errors[$field][$ruleName] = isset($this->customMessages[$field][$ruleName]) ? $this->customMessages[$field][$ruleName] : $message;
    }

    /**
     * explode rules
     *
     * @param mixed $rules
     * @return array
     */
    public function explodeRules($rules)
    {
        if (is_string($rules)) {
            return explode('|', $rules);
        }

        return $rules;
    }

    /**
     * prepare rule
     * explain string rule
     * @param mixed $rule
     * @return mixed
     */
    public function prepareRule($rule)
    {
        if (is_string($rule)) {
            $arr = explode(':', $rule);
            $ruleClass = $arr[0];
            $ruleParameters = isset($arr[1]) ? explode(',', $arr[1]) : null;

            return [$ruleClass, $ruleParameters];
        } else {
            return $rule;
        }
    }

    /**
     * is the rule can be null?
     *
     * @param mixed $ruleObj
     * @return boolean
     */
    public function isNullable($ruleObj)
    {
        return !($ruleObj instanceof Validation\Rule\CallableRule) && !($ruleObj instanceof Validation\Rule\Required);
    }
}
